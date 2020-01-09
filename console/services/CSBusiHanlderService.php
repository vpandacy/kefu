<?php
namespace console\services;
use common\models\uc\Staff;
use common\services\applog\AppLogService;
use common\services\BaseService;
use common\services\chat\ChatEventService;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
use common\services\QueueListService;
use common\services\uc\CustomerService;
use GatewayWorker\Lib\Gateway;
use Workerman\Worker;

class CSBusiHanlderService extends BaseService
{
    /**
     * 这里应该要启动一个text监控，然后将微信客服系统的数据通过tcp传递过来
     * 然后通过这里转发给对应的客服人员
     */
    public static function onWorkerStart(  $worker ) {
        //如果不特殊处理，就会启动多次，而一个端口被占用，在此启动就会报错
        if( $worker->id != 0 ){
            return;
        }
        //再启动一个text协议，用来进行数据转发,
        try{
            $params_inner = $worker->transfer_params;
            if( $params_inner ){
                $inner_worker = new Worker( "text://{$params_inner['host']}" );
                $inner_worker->name = $params_inner['name'];
                $inner_worker->onMessage = function( $connection, $data){
                    try{
                        CSBusiHanlderService::handleInnerMessage($connection,$data);
                    }catch (\Exception $e){
                        ChatEventService::handlerError( $e->getTraceAsString() );
                    }
                };
                // 运行worker
                $inner_worker->listen();
            }
        }catch (\Exception $e){
            ChatEventService::handlerError( $e->getTraceAsString() );
        }
    }

    /**
     * 当游客端浏览器连接时触发
     * 如果业务不需此回调可以删除onConnect
     * @param int $client_id 连接id
     */
    public static function onConnect( $client_id ){
    }

    /**
     * 当游客端浏览器发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     * $message = [
     *      "cmd" => "动作",
     *      "data" => "内容"
     * ];
     */
    public static function onMessage($client_id, $message) {
        try{
            $message = @json_decode($message, true);
            $message = $message??[];
            if( isset( $_SESSION['REMOTE_IP'] ) && isset( $_SERVER['REMOTE_ADDR'] ) ){
                $_SERVER['REMOTE_ADDR'] = $_SESSION['REMOTE_IP'];
            }
            $message = $message + $_SERVER;
            self::consoleLog( var_export( $message,true ) );
            // 处理消息事件.
            self::handleMessage($client_id,$message);
        }catch (\Exception $e){
            ChatEventService::handlerError( $e->getTraceAsString() );
        }
    }

    /**
     * 当客服端用户断开连接时触发
     * 这里的问题是不知道这个client id 对应的客服，所以也要编辑好，不然到时候无法关闭了
     * 注意.这里会存在一个问题.用户每次刷新时.
     * @param int $client_id 连接id
     * @return false
     */
    public static function onClose( $client_id ){
        $cache_params = ChatEventService::getCSBindCache($client_id);
        if(!$cache_params) {
            return false;
        }

//        $cs_sn = $cache_params['f_id'];

        // 更新状态.如果是退出登录了.
//        Staff::updateAll([
//            'is_online'=>ConstantService::$default_status_false,
//            'is_login'=>ConstantService::$default_status_false],['sn'=>$cs_sn]);

//        // 向所有人发送,发消息给对应的游客，然后客服工作台在设置
//        QueueListService::push2Guest(QueueConstant::$queue_guest_chat, [
//            'cmd'   =>  ConstantService::$chat_cmd_kf_logout,
//            'data'  =>  [
//                'f_id'  =>  $cache_params['f_id']
//            ]
//        ]);
//
//         // 下线客服.
//        CustomerService::offlineByCSSN($cache_params['f_id']);
        ChatEventService::clearCSBindCache($client_id);
    }

    /**
     * 处理客服消息事件.
     * @param string $client_id
     * @param array $message
     */
    public static function handleMessage($client_id, $message)
    {
        $data = $message['data'] ?? [];
        $f_id = $data['f_id'] ?? 0;
        switch ($message['cmd']) {
            case ConstantService::$chat_cmd_reply://聊天
                //将消息转发给另一个WS服务组，放入redis，然后通过Job搬运
                QueueListService::push2Guest( QueueConstant::$queue_guest_chat,$message);
                break;
            case ConstantService::$chat_cmd_kf_in://设置绑定关系，使用 Gateway::bindUid(string $client_id, mixed $uid);
                if ($f_id) {
                    // 关闭之前的信息.
                    $client_ids = Gateway::getClientIdByUid($f_id);
                    if($client_ids) {
                        foreach($client_ids as $old_client) {
                            Gateway::sendToClient($old_client, json_encode([
                                // 这里退出.
                                'cmd'   =>  ConstantService::$chat_cmd_kf_logout
                            ]));
                            Gateway::closeClient($old_client);
                        }
                    }
                    //建立绑定关系，后面就可以根据f_id找到这个人了
                    Gateway::bindUid($client_id, $f_id);
                    ChatEventService::setCSBindCache($client_id, [
                        'f_id'    =>  $f_id,
                    ]);
                }
                Gateway::sendToClient($client_id, ChatEventService::buildMsg(ConstantService::$chat_cmd_system,[
                    'content'   =>  '登录成功',
                ]));
                break;
            case ConstantService::$chat_cmd_pong:
                //EventsDispatch::addChatHistory( $client_id,$message );
                break;
            case ConstantService::$chat_cmd_ping:
                //EventsDispatch::addChatHistory( $client_id,$message );
                break;
        }
    }

    /**
     * 处理内部消息.
     * @param $connection
     * @param $data
     * @return mixed
     */
    public static function handleInnerMessage($connection,$data)
    {
        $message = json_decode( $data,true );
        self::consoleLog( var_export( $message,true ) );
        switch ($message['cmd']) {
            case ConstantService::$chat_cmd_kf_health:
                if(!Gateway::isUidOnline($message['data']['sn'])) {
                    $staff = Staff::findOne(['sn'=> $message['data']['sn']]);
                    if(!$staff) {
                        return false;
                    }
                    // 退出操作.
                    AppLogService::addLoginLog($staff['merchant_id'],$staff['id'],1);
                    // 更新客服的在线状态.
                    Staff::updateAll(['is_online' => 0],['sn' => $message['data']['sn']]);
                }
                break;
            default:
                if( isset( $message['data']['t_id']) ) {
                    //发送给对应的人
                    $tmp_client = Gateway::getClientIdByUid( $message['data']['t_id'] );
                    $tmp_client && Gateway::sendToClient( $tmp_client[0], $data );
                }
                $connection->send( "success" );
        }

        return true;
    }
}
<?php

namespace console\services;

use common\services\BaseService;
use common\services\chat\ChatEventService;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\QueueListService;
use GatewayWorker\Lib\Gateway;
use Workerman\Worker;

class GuestBusiHanlderService extends BaseService
{
    /**
     * 这里应该要启动一个text监控，然后将微信客服系统的数据通过tcp传递过来
     * 然后通过这里转发给对应的客服人员
     */
    public static function onWorkerStart($worker)
    {
//如果不特殊处理，就会启动多次，而一个端口被占用，在此启动就会报错
        if( $worker->id != 0 ){
            return;
        }
        //再启动一个text协议，用来进行数据转发,
        $params_inner = $worker->transfer_params;
        if( $params_inner ){
            $inner_worker = new Worker( "text://{$params_inner['host']}" );
            $inner_worker->name = $params_inner['name'];
            $inner_worker->onMessage = function( $connection, $data){
                $message = json_decode( $data,true );
                self::consoleLog( var_export( $message,true ) );
                if( isset( $message['data']['t_id']) ){

                    //发送给对应的人
                    $tmp_client = Gateway::getClientIdByUid( $message['data']['t_id'] );
                    Gateway::sendToClient( $tmp_client[0], $data );
                }
                return $connection->send( "success" );
            };
            // 运行worker
            $inner_worker->listen();
        }
    }

    /**
     * 当游客端浏览器连接时触发
     * 如果业务不需此回调可以删除onConnect
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
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
    public static function onMessage($client_id, $message)
    {
        $message = @json_decode($message, true);
        $message = $message??[];
        if (isset($_SESSION['REMOTE_IP']) && isset($_SERVER['REMOTE_ADDR'])) {
            $_SERVER['REMOTE_ADDR'] = $_SESSION['REMOTE_IP'];
        }
        $message = $message + $_SERVER;
        // @todo 后面考虑将返回信息给统一返回.不然太麻烦了.就返回信息就得写很多.
        $data = $message['data'] ?? [];
        $f_id = $data['f_id'] ?? 0;
        self::consoleLog( var_export( $message,true ) );
        switch ($message['cmd']) {
            case "guest_in"://客户进来到页面，设置绑定关系，使用 Gateway::bindUid(string $client_id, mixed $uid);
                if ($f_id) {
                    //建立绑定关系，后面就可以根据f_id找到这个人了
                    Gateway::bindUid($client_id, $f_id);
                    //后面的输入应该进入redis，存起来，为了后续分析
                }
                $params = [
                    "content" => time()
                ];
                $data = ChatEventService::buildMsg( ConstantService::$chat_cmd_hello,$params );
                Gateway::sendToClient( $client_id,$data );
                break;
            case "guest_connect"://客户链接,要分配客服
                $kf_info = ChatEventService::getKFByRoute( $data['msn'] );
                if( $kf_info ){
                    $params = [
                        "sn" => $kf_info['sn'],
                        "name" => $kf_info['name'],
                        "avatar" => GlobalUrlService::buildPicStaticUrl("hsh",$kf_info['avatar'])
                    ];
                    $data = ChatEventService::buildMsg( ConstantService::$chat_cmd_assign_kf,$params );
                    //转发消息给对应的客服，做好接待准备
                    $transfer_params = [
                        "f_id" => $f_id,
                        "t_id" => $kf_info['sn'],
                        // 随机生成一个昵称.
                        'nickname'  =>  'Guest-' . substr($f_id, strrpos($f_id,'-') + 1),
                        'avatar'    =>  GlobalUrlService::buildPicStaticUrl('hsh',ConstantService::$default_avatar),
                        'allocation_time'   =>  date('H:i:s'),
                    ];
                    $transfer_data = ChatEventService::buildMsg( ConstantService::$chat_cmd_guest_connect,$transfer_params );
                    QueueListService::push2CS( QueueConstant::$queue_cs_chat,json_decode($transfer_data,true) );
                }else{
                    $params = [
                        "content" => ChatEventService::getLastErrorMsg()
                    ];
                    $data = ChatEventService::buildMsg( ConstantService::$chat_cmd_system,$params );
                }
                Gateway::sendToClient( $client_id,$data );
                //找找目前有咩有空闲的客服，找一个在线的客服分配过去
                break;
            case "guest_close":
                if ($f_id) {//也要进入redis，进入数据记录
                    Gateway::unbindUid($client_id, $f_id);
                }
                break;
            case "reply"://客服回复
                //将消息转发给另一个WS服务组，放入redis，然后通过Job搬运
                QueueListService::push2CS( QueueConstant::$queue_cs_chat,$message);
                break;
            case "kf_in"://设置绑定关系，使用 Gateway::bindUid(string $client_id, mixed $uid);
                break;
            case "pong":
                break;
            case "ping":
                Gateway::sendToClient($client_id,json_encode(['cmd'=>'pong']));
                break;
        };
    }

    /**
     * 当游客端用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id)
    {
        // 向所有人发送
    }
}
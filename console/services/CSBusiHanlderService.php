<?php
namespace console\services;
use common\services\BaseService;
use common\services\chat\ChatEventService;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
use common\services\QueueListService;
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
                        $message = json_decode( $data,true );
                        self::consoleLog( var_export( $message,true ) );
                        if( isset( $message['data']['t_id']) ){
                            //发送给对应的人
                            $tmp_client = Gateway::getClientIdByUid( $message['data']['t_id'] );
                            $tmp_client && Gateway::sendToClient( $tmp_client[0], $data );
                        }

                        // 这种方式通知不好.
                        if($message['cmd'] == ConstantService::$chat_cmd_guest_close && isset($message['data']['kf_sn'])) {
                            //发送给对应的人
                            $tmp_client = Gateway::getClientIdByUid( $message['data']['kf_sn'] );
                            $tmp_client && Gateway::sendToClient( $tmp_client[0], $data );
                        }

                        return $connection->send( "success" );
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
            $data = $message['data'] ?? [];
            $f_id = $data['f_id'] ?? 0;
            self::consoleLog( var_export( $message,true ) );
            switch ($message['cmd']) {
                case ConstantService::$chat_cmd_reply://聊天
                    //将消息转发给另一个WS服务组，放入redis，然后通过Job搬运
                    QueueListService::push2Guest( QueueConstant::$queue_guest_chat,$message);
                    break;
                case ConstantService::$chat_cmd_kf_in://设置绑定关系，使用 Gateway::bindUid(string $client_id, mixed $uid);
                    if ($f_id) {
                        //建立绑定关系，后面就可以根据f_id找到这个人了
                        Gateway::bindUid($client_id, $f_id);
                    }
                    break;
                case ConstantService::$chat_cmd_pong:
                    //EventsDispatch::addChatHistory( $client_id,$message );
                    break;
                case ConstantService::$chat_cmd_ping:
                    //EventsDispatch::addChatHistory( $client_id,$message );
                    break;
            };
        }catch (\Exception $e){
            ChatEventService::handlerError( $e->getTraceAsString() );
        }
    }

    /**
     * 当游客端用户断开连接时触发
     * @param int $client_id 连接id
     * 这里的问题是不知道这个client id 对应的客服，所以也要编辑好，不然到时候无法关闭了
     */
    public static function onClose( $client_id ){
        // 向所有人发送,发消息给对应的kf，然后客服工作台在设置
    }
}
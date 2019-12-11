<?php
namespace console\services;
use common\services\BaseService;
use GatewayWorker\Lib\Gateway;

class GuestBusiHanlderService extends BaseService
{
    /**
     * 这里应该要启动一个text监控，然后将微信客服系统的数据通过tcp传递过来
     * 然后通过这里转发给对应的客服人员
     */
    public static function onWorkerStart(  $worker ) {

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
        // 这里是向单个人发送. 及时回复. 先别发送.
        //Gateway::sendToClient( $client_id, $message );
        //return;
        $message = json_decode($message, true);
        if( isset( $_SESSION['REMOTE_IP'] ) && isset( $_SERVER['REMOTE_ADDR'] ) ){
            $_SERVER['REMOTE_ADDR'] = $_SESSION['REMOTE_IP'];
        }
        $message = $message + $_SERVER;
        switch ($message['cmd']) {
            case "guest_in"://客户进来到页面，设置绑定关系，使用 Gateway::bindUid(string $client_id, mixed $uid);
                //EventsDispatch::guestIn($client_id, $message);
                break;
            case "guest_connect"://客户链接,要分配客服
                //找找目前有咩有空闲的客服
                //EventsDispatch::guestConnect($client_id, $message);
                break;
            case "guest_close":
                //EventsDispatch::guestClose($client_id, $message);
                break;
            case "chat"://聊天
                //EventsDispatch::chatMessage( $client_id,$message );
                break;
            case "kf_in"://设置绑定关系，使用 Gateway::bindUid(string $client_id, mixed $uid);
                //EventsDispatch::guestIn($client_id, $message);
                break;
            case "pong":
                //EventsDispatch::addChatHistory( $client_id,$message );
                break;
            case "ping":
                //EventsDispatch::addChatHistory( $client_id,$message );
                break;
        };
    }

    /**
     * 当游客端用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id){
        // 向所有人发送
    }
}
<?php
namespace console\services;
use common\services\BaseService;
use common\services\constant\QueueConstant;
use common\services\QueueListService;
use GatewayWorker\Lib\Gateway;

class CSBusiHanlderService extends BaseService
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
        $data = $message['data'] ?? [];
        $f_id = $data['f_id'] ?? 0;
        switch ($message['cmd']) {
            case "chat"://聊天
                //将消息转发给另一个WS服务组，放入redis，然后通过Job搬运
                QueueListService::push2Guest( QueueConstant::$queue_cs_chat,$message);
                break;
            case "reply":
                //是从游客过来的
                break;
            case "kf_in"://设置绑定关系，使用 Gateway::bindUid(string $client_id, mixed $uid);
                if ($f_id) {
                    //建立绑定关系，后面就可以根据f_id找到这个人了
                    Gateway::bindUid($client_id, $f_id);
                }
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
<?php

namespace console\services;

use common\services\BaseService;
use common\services\constant\QueueConstant;
use common\services\QueueListService;
use GatewayWorker\Lib\Gateway;

class GuestBusiHanlderService extends BaseService
{
    /**
     * 这里应该要启动一个text监控，然后将微信客服系统的数据通过tcp传递过来
     * 然后通过这里转发给对应的客服人员
     */
    public static function onWorkerStart($worker)
    {

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
        switch ($message['cmd']) {
            case "guest_in"://客户进来到页面，设置绑定关系，使用 Gateway::bindUid(string $client_id, mixed $uid);
                if ($f_id) {
                    //建立绑定关系，后面就可以根据f_id找到这个人了
                    Gateway::bindUid($client_id, $f_id);
                    //后面的输入应该进入redis，存起来，为了后续分析
                }
                break;
            case "guest_connect"://客户链接,要分配客服
                //找找目前有咩有空闲的客服，找一个在线的客服分配过去
                break;
            case "guest_close":
                if ($f_id) {//也要进入redis，进入数据记录
                    Gateway::unbindUid($client_id, $f_id);
                }
                break;
            case "chat"://聊天
                //将消息转发给另一个WS服务组，放入redis，然后通过Job搬运
                QueueListService::push2CS( QueueConstant::$queue_guest_chat,$message);
                break;
            case "reply"://客服回复
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
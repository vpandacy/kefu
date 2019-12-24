<?php
namespace console\modules\cs\controllers\queue;

use common\models\uc\Staff;
use common\services\chat\ChatEventService;
use common\services\chat\ChatGroupService;
use common\services\chat\ChatSocketService;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\QueueListService;
use console\controllers\QueueBaseController;

/***
 * Class PushController
 * Author: Guo Wei
 * php yii cs/queue/push/start
 */
class PushController extends QueueBaseController
{
    private  $socket = null;
    public function __construct($id, $module, $config = [])
    {
        $this->queue_name = QueueConstant::$queue_cs_chat;
        $this->instance_name = QueueConstant::$instance_cs;
        parent::__construct($id, $module, $config);
        //初始化socket，这样可以复用socket ，减少初始化的事情
        $config = \Yii::$app->params['cs_1'];
        $url = 'tcp://'.$config['push']['host'];
        $this->socket  = new ChatSocketService( $url );
    }

    protected function handle($data)
    {
        if ( !$data ) {
            return $this->echoLog("no data to handler");
        }
        //需要同时将消息转发一份到对话队列，然后存储起来
        QueueListService::push2ChatDB( QueueConstant::$queue_chat_log,$data );
        //将消息同步到客服ws中心，如果是关闭，还要找到对应的客服是谁，然后通知客服了和 UUID
        $cmd = $data['cmd'];
        switch ($cmd){
            case ConstantService::$chat_cmd_guest_close://客户关闭了ws
                $this->handleGuestClose($data['data']);
                break;
        }
        $ret = $this->socket->send( json_encode( $data ) );
        return $this->echoLog( "ok:".$ret );
    }

    /**
     * 客服关闭聊天.
     * @param array $params
     * @return bool
     */
    protected function handleGuestClose($params)
    {
        // 离开组和等待组信息.
        ChatGroupService::leaveGroup($params['t_id'], $params['f_id']);
        ChatGroupService::leaveWaitGroup($params['t_id'], $params['f_id']);
        // 查询对应组的情况.
        $wait_queue = ChatGroupService::getWaitGroupAllUsers($params['t_id']);
        if(count($wait_queue) <= 0) {
            return true;
        }

        $staff = Staff::findOne(['sn'=>$params['t_id']]);

        $online_users = ChatGroupService::getGroupAllUsers($params['t_id']);

        if(count($online_users) < $staff['listen_nums']) {
            $guest_uuid = array_shift($wait_queue);
            ChatGroupService::leaveWaitGroup($params['t_id'], $guest_uuid);
            ChatGroupService::joinGroup($params['t_id'], $guest_uuid);

            $cs_params = [
                "f_id" => $guest_uuid,
                "t_id" => $params['t_id'],
                // 随机生成一个昵称.
                'nickname'  =>  'Guest-' . substr($guest_uuid, strlen($guest_uuid) - 12),
                'avatar'    =>  GlobalUrlService::buildPicStaticUrl('hsh',ConstantService::$default_avatar),
                'allocation_time'   =>  date('H:i:s'),
            ];

            $cs_params = ChatEventService::buildMsg( ConstantService::$chat_cmd_guest_connect,$cs_params );

            // 将信息发送给客服.通知游客来了.
            QueueListService::push2CS(QueueConstant::$queue_cs_chat, json_decode($cs_params, true));

            // 已经接待成功.
            $guest_params = ChatEventService::buildMsg(ConstantService::$chat_cmd_assign_kf,[
                'f_id'      => $params['t_id'],
                't_id'      => $guest_uuid,
                'sn'        => $params['t_id'],
            ]);

            // 这里是分配客服.
            QueueListService::push2Guest(QueueConstant::$queue_guest_chat, json_decode($guest_params, true));
            // 分配完成过后.给组内的所有客服都添加一条系统消息信息.

        }

        if(!$wait_queue) {
            return true;
        }

        foreach($wait_queue as $key => $guest_uuid) {
            $params = [
                't_id'  =>  $guest_uuid,
                'f_id'  =>  $params['t_id'],
                'sn'    =>  $params['t_id'],
                'wait_num'  =>  $key + 1,   // 当前等待第几位.
            ];

            // 批量通知等待组内所有人当前所在的位置.
            QueueListService::push2Guest(QueueConstant::$queue_guest_chat,[
                'cmd'   =>  ConstantService::$chat_cmd_assign_kf_wait,
                'data'  =>  $params
            ]);
        }
    }

    public function actionTest(){
        $data = [
            "cmd" => "chant",
            "data" => [
                "content" => "你好"
            ]
        ];
        $this->handle( $data );
    }
}
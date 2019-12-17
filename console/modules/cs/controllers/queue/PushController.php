<?php
/**
 * Class PushService
 * Author: Vincent
 * WeChat: apanly
 * CreateTime: 2019/12/12 5:30 PM
 */

namespace console\modules\cs\controllers\queue;


use common\services\chat\ChatSocketService;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
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
                //需要查找对应的客服
                break;
        }
        $ret = $this->socket->send( json_encode( $data ) );
        return $this->echoLog( "ok:".$ret );
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
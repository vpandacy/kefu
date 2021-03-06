<?php

namespace console\modules\guest\controllers\queue;


use common\services\chat\ChatSocketService;
use common\services\constant\QueueConstant;
use common\services\QueueListService;
use console\controllers\QueueBaseController;

/***
 * Class PushController
 * Author: Guo Wei
 * php yii guest/queue/push/start
 */
class PushController extends QueueBaseController
{
    private  $socket = null;

    public function __construct($id, $module, $config = [])
    {
        $this->queue_name = QueueConstant::$queue_guest_chat;
        $this->instance_name = QueueConstant::$instance_quest;
        parent::__construct($id, $module, $config);
        //初始化socket，这样可以复用socket ，减少初始化的事情
        $config = \Yii::$app->params['guest_1'];
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
        //将消息同步到客服ws中心
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
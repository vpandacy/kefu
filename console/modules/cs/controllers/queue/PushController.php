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
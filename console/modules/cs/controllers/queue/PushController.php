<?php
/**
 * Class PushService
 * Author: Vincent
 * WeChat: apanly
 * CreateTime: 2019/12/12 5:30 PM
 */

namespace console\modules\cs\controllers\queue;


use common\services\constant\QueueConstant;
use console\controllers\QueueBaseController;

/***
 * Class PushController
 * Author: Guo Wei
 * php yii cs/queue/push/start
 */
class PushController extends QueueBaseController
{
    public function __construct($id, $module, $config = [])
    {
        $this->queue_name = QueueConstant::$queue_cs_chat;
        $this->instance_name = QueueConstant::$instance_cs;
        parent::__construct($id, $module, $config);
    }

    protected function handle($data)
    {

        if ( !$data ) {
            return $this->echoLog("no data to handler");
        }
        //将消息同步到客服ws中心
        $config = \Yii::$app->params['cs'];
        $url = 'tcp://'.$config['push']['host'];
        $client = stream_socket_client( $url );
        stream_set_timeout( $client,2 );
        fwrite( $client,  json_encode( $data )."\n" );
        $ret = fgets($client, 10);
        fclose( $client);
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
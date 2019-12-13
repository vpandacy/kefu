<?php

namespace console\modules\guest\controllers\queue;


use common\services\constant\QueueConstant;
use console\controllers\QueueBaseController;

/***
 * Class PushController
 * Author: Guo Wei
 * php yii guest/queue/push/start
 */
class PushController extends QueueBaseController
{
    public function __construct($id, $module, $config = [])
    {
        $this->queue_name = QueueConstant::$queue_guest_chat;
        $this->instance_name = QueueConstant::$instance_cs;
        parent::__construct($id, $module, $config);
    }

    protected function handle($data)
    {

        if ( !$data ) {
            return $this->echoLog("no data to handler");
        }
        //将消息同步到客服ws中心
        $config = \Yii::$app->params['guest'];
        $data['cmd'] = "reply";
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
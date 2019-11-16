<?php

namespace console\controllers;

use Workerman\Worker;
use Workerman\Lib\Timer;

$root_path = realpath(__DIR__ . "/../../");
require_once $root_path . '/vendor/workerman/workerman/Autoloader.php';

/**
 * 心跳实现原理：http://doc.workerman.net/faq/heartbeat.html
 */
class TestController extends BaseController
{
    public function __construct($id, $module, $config = [])
    {
        //hack掉workman 根据 $argv 获取参数的问题
        global $argv;
        $argv[0] = $argv[1];
        $argv[1] = $argv[2];
        parent::__construct($id, $module, $config);
    }

    /**
     * php yii gw/index
     */
    public function actionIndex()
    {


        // 注意：这里与上个例子不同，使用的是websocket协议
        $ws_worker = new Worker("websocket://0.0.0.0:2000");
        // 启动4个进程对外提供服务
        $ws_worker->count = 4;
        $ws_worker->name = "kefu_ws";

        // 心跳间隔55秒
        //define('HEARTBEAT_TIME', 5);
        $ws_worker->onWorkerStart = function ($ws_worker) {
//            Timer::add(1, function()use($ws_worker){
//                $time_now = time();
//                foreach( $ws_worker->connections as $connection) {
//                    // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
//                    if ( empty($connection->lastMessageTime) ) {
//                        $connection->lastMessageTime = $time_now;
//                        continue;
//                    }
//                    // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
//                    if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME) {
//                        $connection->close();
//                    }
//                }
//            });
        };
        // 当收到客户端发来的数据后返回hello $data给客户端
        $ws_worker->onMessage = function ($connection, $data) {
            //向客户端发送hello $data
            var_dump( $data );
            $connection->send('hello ' . $data);
        };

        //监听一个text协议
        // 监听一个text端口,我觉得还是用http协议 相对来说比较重
        $inner_http_worker = new Worker( 'text://0.0.0.0:2222' );
        $inner_http_worker->name = "kefu_text";
        // 当http客户端发来数据时触发
        $inner_http_worker->onMessage = function( $connection, $data){
            $message = json_decode( $data,true );
            //EventsDispatch::chatMessage( $message['data']['f_code'],$message );
            return $connection->send( "success" );
        };
        // 运行worker
        Worker::runAll();
    }

}
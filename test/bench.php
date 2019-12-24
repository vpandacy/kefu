<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use \Workerman\Lib\Timer;
use Workerman\Connection\AsyncTcpConnection;
use Workerman\Worker;


$worker = new Worker();

function connect() {
    static $count = 0;
    // 2000个链接
    if ($count++ >= 2000) return;
    // 建立异步链接
    $con = new AsyncTcpConnection('ws://www.kefu.test.hsh568.cn:8230');
    $con->onConnect = function($con){
        // 递归调用connect
        connect();
    };

    $con->onMessage = function($con, $msg) {
        $con->send('{"cmd":"pong"}');
    };

    $con->onClose = function($con) {
        echo "con close\n";
    };

    // 当前链接每10秒发个心跳包
    Timer::add(10, function() use($con) {
        $con->send('{"cmd":"pong"}');
    });

    $con->connect();
    echo $count, " connections complete\n";
}

$worker->onWorkerStart = '\connect';

Worker::runAll();
<?php

namespace console\controllers;

use common\services\QueueListService;
use Workerman\Worker;
$root_path = realpath(__DIR__ . "/../../");
require_once $root_path . '/vendor/workerman/workerman/Autoloader.php';
/**
 * 心跳实现原理：http://doc.workerman.net/faq/heartbeat.html
 */
class TestController extends BaseController
{
    /**
     * php yii test/index
     */
    public function actionIndex(){
        QueueListService::push2CS("a");
    }

    /**
     * php yii test/ws
     */
    public function actionWs(){

    }

    public function actionRun()
    {
        $path = dirname(dirname(__DIR__)) . '/yii';
        while(true) {
            system('php ' . $path . ' cs/queue/push/start');
            system('php ' . $path . ' guest/queue/push/start');
        }
    }
}
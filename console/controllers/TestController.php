<?php

namespace console\controllers;

use common\services\chat\ChatEventService;
use common\services\QueueListService;
use common\services\redis\CacheService;
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
        $group = [
            "6444ffd65f0311eaadedcbdcf868cb51",
            "09bd6f3e5e8c11ea8b8b5b83a3dd4a53",
            "213123312344",
            "888484884",
            "6444ffd65f0311eaadedcbdcf868cb51",
            "09bd6f3e5e8c11ea8b8b5b83a3dd4a53",
        ];
        $str =  serialize( $group );
        CacheService::set("kf_cache_test",$str,86400 * 30);
        var_dump(  @unserialize( $str ) );
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
            system('php ' . $path . ' chat/queue/chat/start');
        }
    }
}
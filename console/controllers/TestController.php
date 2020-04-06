<?php

namespace console\controllers;

use common\components\ip\IPDBQuery;
use common\services\chat\ChatEventService;
use common\services\QueueListService;
use common\services\redis\CacheService;
use function Matrix\trace;
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
        $ret = parse_url("http://www.kf.hsh568.cn/falkspyx/code/mobile?uuid=f5239d86782711eab878a1500f318594&code=in4gbuwm",PHP_URL_QUERY);
        parse_str( $ret,$arr );
        var_dump( $arr );
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
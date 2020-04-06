<?php

namespace console\controllers;

use common\components\helper\IPHelper;
use itbdw\Ip\IpLocation;


/**
 * 心跳实现原理：http://doc.workerman.net/faq/heartbeat.html
 */
class TestController extends BaseController
{
    /**
     * php yii test/index
     */
    public function actionIndex(){
        $ip = "49.234.54.54";
        //$ip = "183.69.218.235";
        var_dump( IpLocation::getLocation( $ip ) );
        var_dump( IPHelper::getIpInfo( $ip ) );
    }
}
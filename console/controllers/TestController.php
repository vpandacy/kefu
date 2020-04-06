<?php

namespace console\controllers;

use common\components\helper\IPHelper;
use common\components\ip\IPDBQuery;
use itbdw\Ip\IpLocation;
use GeoIp2\Database\Reader;

/**
 * 心跳实现原理：http://doc.workerman.net/faq/heartbeat.html
 */
class TestController extends BaseController
{
    /**
     * php yii test/index
     */
    public function actionIndex(){
        $ip = "223.104.48.16";
        //$ip = "183.69.218.235";
        //$ip = "49.234.54.54";
//        try{
//            $reader = new Reader('/data/www/guangzhou/ip_data/geoip/GeoLite2-City.mmdb');
//            $record = $reader->city( $ip );
//            var_dump( $record );exit();
//            var_dump( $record->country->names['zh-CN'] );
//            var_dump( $record->subdivisions[0]->names['zh-CN'] );
//            var_dump( $record->city->names['zh-CN'] );
//            exit();
//        }catch (\Exception $e){
//            var_dump( $e->getMessage() );
//            exit();
//        }

        var_dump( IpLocation::getLocation( $ip ) );
        var_dump(  IPDBQuery::find( $ip ) );
        var_dump( IPHelper::getIpInfo( $ip ) );
    }
}
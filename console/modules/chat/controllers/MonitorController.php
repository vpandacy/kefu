<?php

namespace console\modules\chat\controllers;

use common\services\monitor\WSCenterService;
use console\controllers\BaseController;
use uc\services\UCConstantService;

class MonitorController extends BaseController
{
    /**
     * php yii chat/monitor/scrapy
     * 获取网关，然后根据网关获取gateway 和 busi worker
     */
    public function actionScrapy()
    {
        $params = \Yii::$app->params;
        $guest_config = [];
        $cs_config = [];
        foreach ($params as $_key => $_item ){
            if( mb_stripos( $_key,"guest_" ) !== false ){
                $guest_config[ $_key ] = $_item['register'];
            }
            if( mb_stripos( $_key,"cs_" ) !== false ){
                $cs_config[ $_key ] = $_item['register'];
            }
        }

        //获取register地址
        foreach ( $guest_config as $_key => $_item ){
            $tmp_params = $_item;
            $tmp_params['type'] = UCConstantService::$ws_register;
            WSCenterService::setKFWS( $tmp_params );
        }

        foreach ( $cs_config as $_key => $_item ){
            $tmp_params = $_item;
            $tmp_params['type'] = UCConstantService::$ws_register;
            WSCenterService::setKFWS( $tmp_params );
        }

        return $this->echoLog( "ok" );
    }
}
<?php
/**
 * Class WSCenterService
 * Author: Vincent
 * WeChat: apanly
 * CreateTime: 2019/12/13 11:35 AM
 */

namespace common\services\monitor;

use common\services\BaseService;

class WSCenterService extends BaseService
{
    /**
     * 根据路由获取ws
     * 后面希望这块可以智能路由
     */
    public static function getGuestWSByRoute( $merchant_id = null ){
        /**
         * 根据merchant_id 计算出来对出来一组服务
         */
        $idx = 1;
        $config = \Yii::$app->params[ "guest_{$idx}" ];
        $gateway_idxs = [];
        foreach ( $config as $_key => $_item ){
            if( mb_stripos( $_key,"gateway_" ) !== false ){
                $gateway_idxs[] = str_replace( "gateway_" ,"",$_key );
            }
        }
        $gateway_idx = mt_rand(1,count( $gateway_idxs ) );
        $config = $config[ "gateway_{$gateway_idx}"];
        return $config['ip'].":".$config['port'];
    }

    /**
     * 根据路由获取ws
     * 后面希望这块可以智能路由
     */
    public static function getCSWSByRoute( $merchant_id = null ){
        /**
         * 根据merchant_id 计算出来对出来一组服务（可以求模）
         */
        $idx = 1;
        $config = \Yii::$app->params[ "cs_{$idx}" ];
        $gateway_idxs = [];
        foreach ( $config as $_key => $_item ){
            if( mb_stripos( $_key,"gateway_" ) !== false ){
                $gateway_idxs[] = str_replace( "gateway_" ,"",$_key );
            }
        }
        $gateway_idx = mt_rand(1,count( $gateway_idxs ) );
        $config = $config[ "gateway_{$gateway_idx}"];
        return $config['ip'].":".$config['port'];
    }

    public static function setKFWS($client) {
        var_dump($client);
    }
}
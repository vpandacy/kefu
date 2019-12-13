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
    public static function getGuestWSByRoute( $sn = null ){
        $config = \Yii::$app->params['guest']['gateway'];
        return $config['ip'].":".$config['port'];
    }

    /**
     * 根据路由获取ws
     * 后面希望这块可以智能路由
     */
    public static function getCSWSByRoute( $merchant_id = null ){
        $config = \Yii::$app->params['cs']['gateway'];
        return $config['ip'].":".$config['port'];
    }
}
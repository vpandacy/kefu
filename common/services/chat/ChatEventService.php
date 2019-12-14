<?php
/**
 * Class ChatEventService
 * Author: Vincent
 * WeChat: apanly
 * CreateTime: 2019/12/13 10:34 PM
 */

namespace common\services\chat;


use common\models\uc\Staff;
use common\services\BaseService;
use common\services\ConstantService;
use common\services\uc\MerchantService;
use yii\db\Expression;

class ChatEventService extends BaseService
{
    /**
     * 智能分配客服
     */
    public static function getKFByRoute( $sn = null ){
        if( !$sn ){
            return self::_err("商户信息异常-1~~");
        }

        $merchant_info = MerchantService::getInfoBySn( $sn );
        if( !$merchant_info ){
            return self::_err("商户信息异常-2~~");
        }

        $staff_info = Staff::find()
            ->where([ "status" => ConstantService::$default_status_true ,"merchant_id" => $merchant_info['id'] ])
            ->orderBy(new Expression('rand()'))->limit(1)->asArray()->one();
        if( !$staff_info ){
            return self::_err("暂无客服~~");
        }

        return $staff_info;
    }

    public static function buildMsg( $cmd ,$data ){
        $params = [
            "cmd" => $cmd,
            "data" => $data
        ];
        return json_encode( $params );
    }
}
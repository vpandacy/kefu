<?php
namespace common\services\uc;

use common\components\helper\DateHelper;
use common\models\uc\Merchant;
use common\models\uc\MerchantSetting;
use common\models\uc\Staff;
use common\services\BaseService;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\redis\CacheService;

class MerchantService extends BaseService
{
    public static function checkValid( $saas_merchant_id = 0 ){
        $info = self::getInfoById( $saas_merchant_id );
        if( !$info ){
            return self::_err("商户信息未找到~~");
        }

//        $now = DateHelper::getFormatDateTime( "Y-m-d" );
//        if( $now > $info['valid_to'] ){
//            return self::_err("非常抱歉，系统使用期限已过,请联系管理员~~");
//        }

        return $info;
    }

    public static function getInfoById( $saas_merchant_id = 0 ){
        if( !$saas_merchant_id ){
            return false;
        }
        $cache_key = "merchant_{$saas_merchant_id}";
        $data = CacheService::get( $cache_key );
        if( !$data ) {
            $info = Merchant::find()
                ->where([ 'id'=> $saas_merchant_id ] )
                ->asArray()->one();
            $data = json_encode( $info?:[] );
            CacheService::set($cache_key,$data,86400 * 30 );
        }
        return json_decode( $data,true );
    }

    public static function getInfoBySn( $sn = null ){
        if( !$sn ){
            return false;
        }
        $cache_key = "merchant_{$sn}";
        $data = CacheService::get( $cache_key );
        if( !$data ) {
            $info = Merchant::find()
                ->where([ 'sn'=> $sn,'status'=>ConstantService::$default_status_true])
                ->asArray()->one();
            $data = json_encode( $info?:[] );
            CacheService::set($cache_key,$data,86400 * 30 );
        }
        return json_decode( $data,true );
    }

    /**
     * 创建一个商户.
     * @param int $app_id
     * @param $merchant_name
     * @param $email
     * @param $password
     * @return bool
     */
    public static function createMerchant($app_id, $merchant_name,$email,$password)
    {
        $merchant = new Merchant();
        $now = DateHelper::getFormatDateTime();

        $merchant->setAttributes([
            'status'    =>  -2,
            'sn'        =>  CommonService::genUniqueName(),
            'name'      =>  $merchant_name,
            'email'         =>  $email,
            'app_id'    =>  $app_id,
            'created_time'  =>  $now,
            'updated_time'  =>  $now
        ],0);

        if(!$merchant->save(0)) {
            return self::_err('数据库保存失败,请联系管理员');
        }

        $employee = new Staff();
        $salt = CommonService::genUniqueName();
        $password = md5($merchant['id'] . '-' . $password  . '-' . $salt);
        $employee->setAttributes([
            'merchant_id'   =>  $merchant->primaryKey,
            'sn'            =>  CommonService::genUniqueName(),
            'app_ids'       =>  implode('',[',', $app_id, ',']),
            'name'          =>  $merchant_name,
            'email'         =>  $email,
            'password'      =>  $password,
            'listen_nums'   =>  0,
            'status'        =>  ConstantService::$default_status_true,
            'is_root'       =>  1,
            'created_time'  =>  $now,
            'updated_time'  =>  $now,
            'salt'          =>  $salt,
        ],0);

        if(!$employee->save(0)) {
            return self::_err('数据库保存失败,请联系管理员');
        }

        return true;
    }


    public static function getConfig( $merchant_id ){
        if( !$merchant_id ){
            return false;
        }
        $cache_key = "merchant_config_{$merchant_id}";
        $data = CacheService::get( $cache_key );
        if( !$data ) {
            $config = MerchantSetting::find()
                ->where(['merchant_id' => $merchant_id])
                ->asArray()->one();
            $data = json_encode( $config?:[] );
            CacheService::set($cache_key,$data,86400 * 30 );
        }
        return json_decode( $data,true );
    }

}
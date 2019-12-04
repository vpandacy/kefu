<?php
namespace www\modules\merchant\service;

use common\models\merchant\Merchant;
use common\models\merchant\Staff;
use common\services\BaseService;
use common\services\CommonService;
use common\services\ConstantService;

class MerchantService extends BaseService
{
    /**
     * 创建一个商户.
     * @param $merchant_name
     * @param $email
     * @param $password
     * @return bool
     */
    public static function createMerchant($merchant_name,$email,$password)
    {
        $merchant = new Merchant();

        $now = date('Y-m-d H:i:s');

        $merchant->setAttributes([
            'status'    =>  -2,
            'sn'        =>  CommonService::genUniqueName(),
            'name'      =>  $merchant_name,
            'email'         =>  $email,
            'created_time'  =>  $now,
            'updated_time'  =>  $now
        ]);

        if(!$merchant->save(0)) {
            return self::_err('数据库保存失败,请联系管理员');
        }

        $employee = new Staff();
        $salt = CommonService::genUniqueName();
        $password = md5($merchant['id'] . '-' . $password  . '-' . $salt);
        $employee->setAttributes([
            'merchant_id'   =>  $merchant->primaryKey,
            'sn'            =>  CommonService::genUniqueName(),
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
}
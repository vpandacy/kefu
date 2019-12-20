<?php
namespace common\services\uc;

use common\models\uc\Staff;
use common\services\BaseService;
use common\services\ConstantService;

class CustomerService extends BaseService
{
    /**
     * 客服下线.
     * @param $sn
     * @return bool
     */
    public static function offlineByCSSN($sn)
    {
        $staff = Staff::findOne(['sn'=>$sn]);

        if(!$staff) {
            return true;
        }

        $staff['is_online'] = ConstantService::$default_status_false;

        return $staff->save() === false;
    }
}
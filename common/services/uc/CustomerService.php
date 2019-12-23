<?php
namespace common\services\uc;

use common\models\uc\Staff;
use common\services\BaseService;

class CustomerService extends BaseService
{
    /**
     * 客服下线.
     * @param $sn
     * @param $status
     * @return bool
     */
    public static function updateOnlineStatus($sn, $status = 0)
    {
        $staff = Staff::findOne(['sn'=>$sn]);

        if(!$staff) {
            return true;
        }

        $staff['is_online'] = $status;
        return $staff->save() === false;
    }
}
<?php
namespace www\modules\merchant\service;

use common\models\merchant\StaffRole;
use common\services\BaseService;

class RoleService extends BaseService
{
    public static function createRoleMapping($staff_id, $role_ids)
    {
        if(StaffRole::updateAll(['status'=>0],['staff_id'=>$staff_id]) === false) {
            return self::_err('保存数据失败,请联系管理员');
        }

        $insert_data = array_map(function ($role_id) use($staff_id) {
            return [
                'staff_id'  =>  $staff_id,
                'role_id'   =>  $role_id,
                'status'    =>  1
            ];
        }, $role_ids);


        $ret = StaffRole::getDb()->createCommand()
            ->batchInsert(StaffRole::getTableSchema(), ['staff_id','role_id','status'], $insert_data)
            ->execute();

        if($ret === false) {
            return self::_err('数据保存失败,请联系管理员');
        }

        return true;
    }
}
<?php
namespace www\modules\merchant\service;

use common\models\merchant\Action;
use common\models\merchant\Role;
use common\models\merchant\RoleAction;
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

    /**
     * 根据员工ID,来获取对应的角色下的所有urls.
     * @param $staff_id
     * @return array
     */
    public static function getRoleUrlsByStaffId($staff_id)
    {
        $role_ids = Role::find()
            ->where([
                'staff_id'  =>  $staff_id,
                'status'    =>  1
            ])
            ->select(['role_id'])
            ->column();

        if(!$role_ids) {
            return [];
        }

        $action_ids = RoleAction::find()
            ->where(['role_id'=>$role_ids,'status' => 1])
            ->select(['action_id'])
            ->column();

        if(!$action_ids) {
            return [];
        }

        $action_urls = Action::find()
            ->where(['id'=>$action_ids,'status'=>1])
            ->select(['urls'])
            ->column();

        // 开始更新所有的信息.
        $urls = [];
        foreach($action_urls as $action_url) {
            $urls = array_merge($urls, implode(',', $action_urls));
        }

        return $urls;
    }
}
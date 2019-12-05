<?php
namespace common\services\uc;

use common\models\uc\Action;
use common\models\uc\RoleAction;
use common\models\uc\StaffRole;
use common\services\BaseService;
use common\services\ConstantService;

class RoleService extends BaseService
{
    /**
     * 给员工添加新的角色.批量.
     * @param $staff_id
     * @param $role_ids
     * @return bool
     * @throws \Exception
     */
    public static function createRoleMapping($staff_id, $app_id, $role_ids)
    {
        $condition = [
            'staff_id'=>$staff_id,
            'app_id'=>$app_id
        ];

        $ret = StaffRole::updateAll(['status' => ConstantService::$default_status_false],$condition);

        if($ret === false) {
            return self::_err('保存数据失败,请联系管理员');
        }

        $insert_data = array_map(function ($role_id) use($staff_id, $app_id) {
            return [
                'staff_id'  =>  $staff_id,
                'role_id'   =>  $role_id,
                'app_id'    =>  $app_id,
                'status'    =>  ConstantService::$default_status_true
            ];
        }, $role_ids);


        $ret = StaffRole::getDb()->createCommand()
            ->batchInsert(StaffRole::tableName(), ['staff_id','role_id', 'app_id', 'status'], $insert_data)
            ->execute();

        if($ret === false) {
            return self::_err('数据保存失败,请联系管理员');
        }

        return true;
    }

    /**
     * 根据员工ID,来获取对应的角色下的所有urls.
     * @param int $app_id 应用ID.
     * @param int $staff_id 员工ID.
     * @param bool $is_root 是否是超级管理员.
     * @return array
     */
    public static function getRoleUrlsByStaffId($app_id,$staff_id, $is_root = false)
    {
        if($is_root) {
            $action_urls = Action::find()
                ->where(['status' => ConstantService::$default_status_true, 'app_id' => $app_id])
                ->select(['urls'])
                ->column();
        }else{
            $role_ids = StaffRole::find()
                ->where([
                    'staff_id'  =>  $staff_id,
                    'app_id'    =>  $app_id,
                    'status'    =>  ConstantService::$default_status_true
                ])
                ->select(['role_id'])
                ->column();

            if(!$role_ids) {
                return [];
            }

            $action_ids = RoleAction::find()
                ->where([
                    'role_id'   => $role_ids,
                    'status'    => ConstantService::$default_status_true,
                    'app_id'    =>  $app_id,
                ])
                ->select(['action_id'])
                ->column();

            if(!$action_ids) {
                return [];
            }

            $action_urls = Action::find()
                ->where([
                    'id'    =>  $action_ids,
                    'status'=>  ConstantService::$default_status_true,
                    'app_id'    =>  $app_id,
                ])
                ->select(['urls'])
                ->column();
        }

        // 开始更新所有的信息.
        $urls = [];
        foreach($action_urls as $action_url) {
            $urls = array_merge($urls, explode(',', $action_url));
        }

        return $urls;
    }
}
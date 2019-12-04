<?php
namespace www\modules\merchant\service;

use common\models\merchant\Action;
use common\models\merchant\Role;
use common\models\merchant\RoleAction;
use common\models\merchant\StaffRole;
use common\services\BaseService;
use common\services\ConstantService;

class RoleService extends BaseService
{
    /**
     * 给员工添加新的角色.批量.
     * @param $staff_id
     * @param $role_ids
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function createRoleMapping($staff_id, $role_ids)
    {
        if(StaffRole::updateAll(['status' => ConstantService::$default_status_false],['staff_id'=>$staff_id]) === false) {
            return self::_err('保存数据失败,请联系管理员');
        }

        $insert_data = array_map(function ($role_id) use($staff_id) {
            return [
                'staff_id'  =>  $staff_id,
                'role_id'   =>  $role_id,
                'status'    =>  ConstantService::$default_status_true
            ];
        }, $role_ids);


        $ret = StaffRole::getDb()->createCommand()
            ->batchInsert(StaffRole::tableName(), ['staff_id','role_id','status'], $insert_data)
            ->execute();

        if($ret === false) {
            return self::_err('数据保存失败,请联系管理员');
        }

        return true;
    }

    /**
     * 根据员工ID,来获取对应的角色下的所有urls.
     * @param $staff_id
     * @param bool $is_root
     * @return array
     */
    public static function getRoleUrlsByStaffId($staff_id, $is_root = false)
    {
        if($is_root) {
            $action_urls = Action::find()
                ->where(['status' => ConstantService::$default_status_true])
                ->select(['urls'])
                ->column();
        }else{
            $role_ids = StaffRole::find()
                ->where([
                    'staff_id'  =>  $staff_id,
                    'status'    =>  ConstantService::$default_status_true
                ])
                ->select(['role_id'])
                ->column();

            if(!$role_ids) {
                return [];
            }

            $action_ids = RoleAction::find()
                ->where(['role_id'=>$role_ids,'status' => ConstantService::$default_status_true])
                ->select(['action_id'])
                ->column();

            if(!$action_ids) {
                return [];
            }

            $action_urls = Action::find()
                ->where(['id'=>$action_ids,'status'=>ConstantService::$default_status_true])
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
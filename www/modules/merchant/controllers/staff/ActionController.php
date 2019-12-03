<?php
namespace www\modules\merchant\controllers\staff;

use common\models\merchant\Action;
use common\models\merchant\Role;
use common\models\merchant\RoleAction;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

class ActionController extends BaseController
{
    public function actionIndex()
    {
        $roles = Role::find()
            ->where(['status'=>1,'merchant_id'=>$this->getMerchantId()])
            ->asArray()
            ->all();

        // 获取所有的权限列表.有权限就过滤掉.没有就不算.
        $actions = Action::find()
            ->where([
                'status'    =>  1
            ])
            ->orderBy([
                'level1_weight' =>  SORT_ASC,
                'level2_weight' =>  SORT_ASC,
                'weight' => SORT_ASC
            ])
            ->asArray()
            ->all();

        if(!$actions) {
            return $this->render('index',[
                'roles' =>  $roles,
                'permissions'    =>  [],
            ]);
        }

        $permissions = [];
        foreach ($actions as $_item) {
            $tmp_level1_key = $_item['level1_name'];
            $tmp_level2_key = $_item['level2_name'];
            if (!isset($permissions[ $tmp_level1_key ])) {
                $permissions[ $tmp_level1_key ] = [
                    'name' => $tmp_level1_key,
                    'counter' => 0,
                    'child' => []
                ];
            }

            if( !in_array( $tmp_level2_key,array_keys( $permissions[$tmp_level1_key]['child'] ) ) ){
                $permissions[ $tmp_level1_key ]['child'][ $tmp_level2_key ] = [
                    'name' => $tmp_level2_key,
                    'counter' => 0,
                    'acts' => []
                ];
            }

            $permissions[$tmp_level1_key]['counter'] += 1;
            $permissions[$tmp_level1_key]["child"][ $tmp_level2_key ]['counter'] += 1;

            $permissions[ $tmp_level1_key ]["child"][ $tmp_level2_key ]['acts'][] = [
                'id' => $_item['id'],
                'name' => $_item['name'],
            ];
        }

        return $this->render('index',[
            'roles' =>  $roles,
            'permissions'   =>  $permissions
        ]);
    }

    /**
     * 根据角色来获取所有的权限ID
     */
    public function actionList()
    {
        $role_id = $this->post('role_id',0);

        if(!$role_id) {
            return $this->renderJSON([],'非法请求', ConstantService::$response_code_fail);
        }

        $role = Role::findOne(['id'=>$role_id,'status'=>1,'merchant_id'=>$this->getMerchantId()]);

        if(!$role) {
            return $this->renderJSON([],'没有找到对应的角色', ConstantService::$response_code_fail);
        }

        $action_ids = RoleAction::find()
            ->where([
                'status'    =>  1,
                'role_id'   =>  $role_id
            ])
            ->select(['action_id'])
            ->column();

        return $this->renderJSON($action_ids, '获取成功', ConstantService::$response_code_success);
    }

    /**
     * 数据保存.
     * @return \yii\console\Response|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionSave()
    {
        $role_id = $this->post('role_id',0);

        if(!$role_id) {
            return $this->renderJSON([],'非法请求', ConstantService::$response_code_fail);
        }

        $role = Role::findOne(['id'=>$role_id,'status'=>1,'merchant_id'=>$this->getMerchantId()]);

        if(!$role) {
            return $this->renderJSON([],'没有找到对应的角色', ConstantService::$response_code_fail);
        }

        $permission_ids = $this->post('permissions');

        if(!$permission_ids || count($permission_ids) <= 0) {
            return $this->renderJSON([],'请选择需要保存的权限', ConstantService::$response_code_fail);
        }

        $actions = Action::find()
            ->where([
                'status'    =>  1
            ])
            ->select(['id'])
            ->column();

        if(array_diff($permission_ids, $actions)) {
           return $this->renderJSON([],'您选择了不存在权限',ConstantService::$response_code_fail);
        }

        // 更新之前的所有信息.在插入即可.
        if(RoleAction::updateAll(['status'=>0],['role_id'=>$role_id]) === false ) {
            return $this->renderJSON([],'数据保存失败,请联系管理员', ConstantService::$response_code_fail);
        }

        $insert_data = array_map(function($permission_id) use($role_id){
            return [
                'status'    =>  1,
                'role_id'   =>  $role_id,
                'action_id' =>  $permission_id
            ];
        }, $permission_ids);

        $ret = RoleAction::getDb()->createCommand()
            ->batchInsert(RoleAction::tableName(),['status','role_id','action_id'],$insert_data)
            ->execute();

        if(!$ret) {
            return $this->renderJSON([],'数据保存失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'数据保存成功', ConstantService::$response_code_success);
    }
}
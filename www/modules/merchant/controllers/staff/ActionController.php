<?php
namespace www\modules\merchant\controllers\staff;

use common\models\merchant\Action;
use common\models\merchant\Role;
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
                'permission'    =>  [],
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
}
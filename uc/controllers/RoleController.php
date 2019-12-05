<?php

namespace uc\controllers;

use common\models\uc\Role;
use common\services\ConstantService;
use uc\controllers\common\BaseController;

class RoleController extends BaseController
{
    /**
     * 角色列表.
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取所有的角色列表.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionList()
    {
        $roles = Role::find()
            ->where([
                'merchant_id'   =>  $this->getMerchantId(),
                'app_id'        =>  $this->getAppId()
            ])
            ->asArray()
            ->all();

        return $this->renderPageJSON($roles,'获取成功', ConstantService::$response_code_page_success);
    }

    /**
     * 角色表存或添加.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionSave()
    {
        $id = intval($this->post('id',0));

        $name= $this->post('name','');

        if(!$name || mb_strlen($name) > 255) {
            return $this->renderJSON([],'请输入正确的部门名称', ConstantService::$response_code_fail);
        }

        $role = $id > 0 ? Role::findOne(['id'=>$id,'merchant_id'=>$this->getMerchantId(),'app_id'=>$this->getAppId()]) : new Role();

        if($id > 0 && !$role) {
            return $this->renderJSON([],'不存在该角色记录', ConstantService::$response_code_fail);
        }

        $role->setAttributes([
            'status'        =>  $id > 0 ? $role['status'] : ConstantService::$default_status_true,
            'merchant_id'   =>  $this->getMerchantId(),
            'app_id'        =>  $this->getAppId(),
            'name'          =>  $name,
        ],0);

        if(!$role->save(0)) {
            return $this->renderJSON([],'数据保存失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'保存成功', ConstantService::$response_code_success);
    }

    /**
     * 禁用.
     */
    public function actionDisable()
    {
        $id = $this->post('id',0);
        if(!$id || !is_numeric($id)) {
            return $this->renderJSON([],'请选择正确的帐号', ConstantService::$response_code_fail);
        }

        $role = Role::findOne(['id'=>$id,'merchant_id'=>$this->getMerchantId(),'app_id'=>$this->getAppId()]);

        if($role['status'] != ConstantService::$default_status_true) {
            return $this->renderJSON([],'该角色已经被禁用了,不需要禁用', ConstantService::$response_code_fail);
        }

        $role['status'] = 0;
        if(!$role->save(0)) {
            return $this->renderJSON([],'操作失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'操作成功', ConstantService::$response_code_success);
    }

    /**
     * 恢复.
     */
    public function actionRecover()
    {
        $ids = $this->post('ids');

        if(!count($ids)) {
            return $this->renderJSON([],'请选择需要恢复的帐号', ConstantService::$response_code_fail);
        }

        if(!Role::updateAll(['status'=>ConstantService::$default_status_true],[
            'id'=>$ids,
            'merchant_id'=>$this->getMerchantId(),
            'app_id'=>$this->getAppId()
        ])) {
            return $this->renderJSON([],'恢复失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'恢复成功', ConstantService::$response_code_success);
    }
}
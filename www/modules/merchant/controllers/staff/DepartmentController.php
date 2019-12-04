<?php

namespace www\modules\merchant\controllers\staff;

use common\models\merchant\Department;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

/**
 * Default controller for the `merchant` module
 */
class DepartmentController extends BaseController
{
    /**
     * 首页.
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取列表.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionList()
    {
        $departments = Department::find()
            ->where([
                'merchant_id'   =>  $this->getMerchantId(),
            ])
            ->asArray()
            ->all();

        return $this->renderPageJSON($departments, '获取成功', ConstantService::$response_code_page_success);
    }

    /**
     * 部门保存或添加.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionSave()
    {
        $id = intval($this->post('id',0));

        $name= $this->post('name','');

        if(!$name || mb_strlen($name) > 255) {
            return $this->renderJSON([],'请输入正确的部门名称', ConstantService::$response_code_fail);
        }

        $department = $id > 0 ? Department::findOne(['id'=>$id,'merchant_id'=>$this->getMerchantId()]) : new Department();

        if($id > 0 && !$department) {
            return $this->renderJSON([],'不存在该部门记录', ConstantService::$response_code_fail);
        }

        $department->setAttributes([
            'status'    =>  $id > 0 ? $department['status'] : ConstantService::$default_status_true,
            'merchant_id'   =>  $this->getMerchantId(),
            'name'      =>  $name,
        ],0);

        if(!$department->save(0)) {
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

        $department = Department::findOne(['id'=>$id,'merchant_id'=>$this->getMerchantId()]);

        if($department['status'] != 1) {
            return $this->renderJSON([],'该部门已经被禁用了,不需要禁用', ConstantService::$response_code_fail);
        }

        $department['status'] = 0;
        if(!$department->save(0)) {
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

        if(!Department::updateAll(['status'=>ConstantService::$default_status_true],['id'=>$ids,'merchant_id'=>$this->getMerchantId()])) {
            return $this->renderJSON([],'恢复失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'恢复成功', ConstantService::$response_code_success);
    }
}

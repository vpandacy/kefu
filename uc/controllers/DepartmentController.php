<?php
namespace uc\controllers;

use common\components\helper\ValidateHelper;
use common\models\uc\Department;
use common\services\ConstantService;
use uc\controllers\common\BaseController;

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
        if($this->isGet()) {
            return $this->render('index');
        }

        $page = intval($this->post('page',1));

        $query = Department::find()->where(['merchant_id'=>$this->getMerchantId(),'app_id'=>$this->getAppId()]);

        $total = $query->count();

        $departments = $query->asArray()
            ->limit($this->page_size)
            ->orderBy(['id'=>SORT_DESC])
            ->offset(($page - 1) * $this->page_size)
            ->all();

        return $this->renderPageJSON($departments, '获取成功', $total);
    }

    /**
     * 部门保存或添加.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionSave()
    {
        $id = intval($this->post('id',0));

        $name= $this->post('name','');

        if(!ValidateHelper::validLength($name, 1, 255)) {
            return $this->renderErrJSON( '请输入正确的部门名称' );
        }

        $department = $id > 0
            ? Department::findOne(['id'=>$id,'merchant_id'=>$this->getMerchantId(),'app_id'=>$this->getAppId()])
            : new Department();

        if($id > 0 && !$department) {
            return $this->renderErrJSON( '不存在该部门记录' );
        }

        $department->setAttributes([
            'status'    =>  $id > 0 ? $department['status'] : ConstantService::$default_status_true,
            'merchant_id'   =>  $this->getMerchantId(),
            'app_id'    =>  $this->getAppId(),
            'name'      =>  $name,
        ],0);

        if(!$department->save(0)) {
            return $this->renderErrJSON( '数据保存失败,请联系管理员' );
        }

        return $this->renderJSON([],'保存成功');
    }

    /**
     * 禁用.
     */
    public function actionDisable()
    {
        $id = $this->post('id',0);
        if(!$id || !is_numeric($id)) {
            return $this->renderErrJSON( '请选择正确的帐号' );
        }

        $department = Department::findOne(['id'=>$id,'merchant_id'=>$this->getMerchantId(),'app_id'=>$this->getAppId()]);

        if($department['status'] != ConstantService::$default_status_true) {
            return $this->renderErrJSON('该部门已经被禁用了,不需要禁用' );
        }

        $department['status'] = 0;
        if(!$department->save(0)) {
            return $this->renderErrJSON( '操作失败,请联系管理员' );
        }

        return $this->renderJSON( [],'操作成功' );
    }

    /**
     * 恢复.
     */
    public function actionRecover()
    {
        $ids = $this->post('ids');

        if(!count($ids)) {
            return $this->renderErrJSON('请选择需要恢复的帐号');
        }

        if(!Department::updateAll(['status'=>ConstantService::$default_status_true],[
            'id'=>$ids,
            'merchant_id'=>$this->getMerchantId(),
            'app_id'=>$this->getAppId()
        ])) {
            return $this->renderErrJSON( '恢复失败,请联系管理员' );
        }

        return $this->renderJSON([],'恢复成功');
    }
}

<?php

namespace www\modules\merchant\controllers;

use common\models\Employees;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

/**
 * Default controller for the `merchant` module
 */
class UserController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'basic';
        return $this->render('login');
    }

    /**
     * 登录操作.
     */
    public function actionSignIn()
    {
        $mobile = $this->post('mobile','');
        $password = $this->post('password','');

        if(!preg_match('/^1\d{10}$/',$mobile)) {
            return $this->renderJSON([],'请输入正确的手机号', ConstantService::$response_code_fail);
        }

        if(!$password) {
            return $this->renderJSON([],'请输入密码', ConstantService::$response_code_fail);
        }

        // 开始检查.
        $employee = Employees::findOne(['mobile'=>$mobile]);

        if(!$employee) {
            return $this->renderJSON([],'暂无该员工信息.', ConstantService::$response_code_fail);
        }

        if($employee['password'] != $this->genPassword($employee['merchant_id'], $password, $employee['salt'])) {
            return $this->renderJSON([],'请输入正确的密码', ConstantService::$response_code_fail);
        }

        // 开始创建登录的信息.
        $this->createLoginStatus($employee);

        return $this->renderJSON([],'登录成功', ConstantService::$response_code_success);
    }
}

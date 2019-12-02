<?php

namespace www\modules\merchant\controllers;

use common\models\Employees;
use common\models\Merchants;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;
use www\modules\merchant\service\MerchantService;

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
        $employee = Employees::findOne(['mobile'=>$mobile,'status'=>0]);

        if(!$employee) {
            return $this->renderJSON([],'暂无该员工信息.', ConstantService::$response_code_fail);
        }

        if($employee['password'] != $this->genPassword($employee['merchant_id'], $password, $employee['salt'])) {
            return $this->renderJSON([],'请输入正确的密码', ConstantService::$response_code_fail);
        }

        $merchant =Merchants::findOne(['id'=>$employee['merchant_id'],'status'=>0]);

        if(!$merchant) {
            return $this->renderJSON([],'该商户已经被禁止登录了.', ConstantService::$response_code_fail);
        }

        // 开始创建登录的信息.
        $this->createLoginStatus($employee);

        return $this->renderJSON([],'登录成功', ConstantService::$response_code_success);
    }

    /**
     * 创建一个商户.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionRegister()
    {
        $mobile = $this->post('mobile','');

        $merchant_name = $this->post('merchant_name','');

        $password = $this->post('password','');

        if(!preg_match('/^1\d{10}$/',$mobile)) {
            return $this->renderJSON([],'请输入正确的手机号', ConstantService::$response_code_fail);
        }

        if(!$password) {
            return $this->renderJSON([],'请输入密码', ConstantService::$response_code_fail);
        }

        if(!$merchant_name) {
            return $this->renderJSON([],'请输入正确的商户名', ConstantService::$response_code_fail);
        }

        $merchant = Merchants::findOne(['merchant_name' => $merchant_name]);
        if($merchant) {
            return $this->renderJSON([],'该商户名或姓名已经被使用了', ConstantService::$response_code_fail);
        }

        if(!MerchantService::createMerchant($merchant_name, $mobile, $password)){
            return $this->renderJSON([],MerchantService::getLastErrorMsg(), ConstantService::$response_code_fail);
        }

        return $this->renderJSON([], '创建成功,请登录商户', ConstantService::$response_code_success);
    }
}

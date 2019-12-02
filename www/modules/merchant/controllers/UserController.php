<?php
namespace www\modules\merchant\controllers;

use common\models\merchant\Merchant;
use common\models\merchant\Staff;
use common\services\ConstantService;
use common\services\GlobalUrlService;
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
        $email = $this->post('email','');
        $password = $this->post('password','');

        if(strpos($email,'@') < 1) {
            return $this->renderJSON([],'请输入正确的手机号', ConstantService::$response_code_fail);
        }

        if(!$password) {
            return $this->renderJSON([],'请输入密码', ConstantService::$response_code_fail);
        }

        // 开始检查.
        $staff = Staff::findOne(['email'=>$email,'status'=>0]);

        if(!$staff) {
            return $this->renderJSON([],'暂无该员工信息.', ConstantService::$response_code_fail);
        }

        if($staff['password'] != $this->genPassword($staff['merchant_id'], $password, $staff['salt'])) {
            return $this->renderJSON([],'请输入正确的密码', ConstantService::$response_code_fail);
        }

        $merchant = Merchant::findOne(['id'=>$staff['merchant_id'],'status'=>0]);

        if(!$merchant) {
            return $this->renderJSON([],'该商户已经被禁止登录了.', ConstantService::$response_code_fail);
        }

        // 开始创建登录的信息.
        $this->createLoginStatus($staff);

        return $this->renderJSON([],'登录成功', ConstantService::$response_code_success);
    }

    /**
     * 创建一个商户.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionRegister()
    {
        $email = $this->post('email','');

        $merchant_name = $this->post('merchant_name','');

        $password = $this->post('password','');

        if(strpos($email,'@') < 1) {
            return $this->renderJSON([],'请输入正确的手机号', ConstantService::$response_code_fail);
        }

        if(!$password) {
            return $this->renderJSON([],'请输入密码', ConstantService::$response_code_fail);
        }

        if(!$merchant_name) {
            return $this->renderJSON([],'请输入正确的商户名', ConstantService::$response_code_fail);
        }

        $merchant = Merchant::findOne(['name' => $merchant_name]);
        if($merchant) {
            return $this->renderJSON([],'该商户名或姓名已经被使用了', ConstantService::$response_code_fail);
        }

        if(!MerchantService::createMerchant($merchant_name, $email, $password)){
            return $this->renderJSON([],MerchantService::getLastErrorMsg(), ConstantService::$response_code_fail);
        }

        return $this->renderJSON([], '创建成功,请登录商户', ConstantService::$response_code_success);
    }

    /**
     * 退出登录操作.
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $this->removeCookie($this->merchant_cookie_name,'','/merchant');
        return $this->redirect(GlobalUrlService::buildMerchantUrl('/'));
    }
}

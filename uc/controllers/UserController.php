<?php
namespace uc\controllers;

use common\components\helper\ValidateHelper;
use common\models\uc\Merchant;
use common\models\uc\Staff;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\uc\MerchantService;
use uc\controllers\common\BaseController;
use uc\services\UCUrlService;

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
        if ($this->isGet()) {
            if ( $this->checkLoginStatus() ) {
                return $this->redirect(UCUrlService::buildUCUrl("/default/application",$this->app_id));
            }
            $this->layout = 'basic';
            return $this->render('login');
        }
        $email = $this->post('email','');
        $password = $this->post('password','');

        if(!ValidateHelper::validEmail($email)) {
            return $this->renderErrJSON('请输入正确的邮箱~~' );
        }

        if(!$password) {
            return $this->renderErrJSON('请输入密码');
        }

        // 开始检查.
        $staff_info = Staff::findOne([ 'email' => $email,'status' => ConstantService::$default_status_true ]);

        if( !$staff_info ) {
            return $this->renderErrJSON('登录失败，请检查用户名和密码~~');
        }

        if($staff_info['password'] != $this->genPassword($staff_info['merchant_id'], $password, $staff_info['salt'])) {
            return $this->renderErrJSON('请输入正确的密码');
        }

        $merchant_info = MerchantService::getInfoById( $staff_info['merchant_id'] );
        if( !$merchant_info || !$merchant_info['status'] ) {
            return $this->renderErrJSON('登录失败，商户信息状态异常~~');
        }

        if( $merchant_info['status'] == -2 ){
            return $this->renderErrJSON('商户信息审核中，请联系管理员加快审核~~');
        }
        // 开始创建登录的信息.
        $this->createLoginStatus( $staff_info );
        $data = [
            "url" => $url ?? UCUrlService::buildUCUrl("/default/application",$this->app_id)
        ];
        return $this->renderJSON($data,'登录成功~~~~' );
    }

    public function actionRegister()
    {
        $email = $this->post('email','');
        $merchant_name = $this->post('merchant_name','');
        $password = $this->post('password','');

        if(!ValidateHelper::validEmail($email)) {
            return $this->renderErrJSON( '请输入正确的邮箱~~' );
        }

        if(ValidateHelper::validIsEmpty($password)) {
            return $this->renderErrJSON( '请输入密码~~' );
        }

        // 检查密码强度.
        if($password && !CommonService::checkPassLevel($password)) {
            return $this->renderErrJSON(  CommonService::getLastErrorMsg() );
        }

        if(ValidateHelper::validIsEmpty($merchant_name)) {
            return $this->renderErrJSON( '请输入正确的商户名~~' );
        }

        $merchant = Merchant::findOne(['name' => $merchant_name]);
        if($merchant) {
            return $this->renderErrJSON( '该商户名已经被使用了~~' );
        }

        if(!MerchantService::createMerchant($this->getAppId(), $merchant_name, $email, $password)){
            return $this->renderErrJSON( MerchantService::getLastErrorMsg() );
        }

        return $this->renderJSON( [], '创建成功,请登录商户~~' );
    }

    /**
     * 退出登录操作.
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $cookie = \Yii::$app->params['cookies']['staff'];

        // 更新在线的状态.如果是退出登录了.
        Staff::updateAll(['is_online'=>ConstantService::$default_status_false],['id'=>$this->current_user['id']]);

        // @todo 这里还要通知游客客服已经退出登录了.

        $this->removeCookie($cookie['name'],$cookie['domain']);
        return $this->redirect(GlobalUrlService::buildKFMerchantUrl('/'));
    }

    /**
     * 获取用户中心数据.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionCenter()
    {
        $content = $this->renderPartial('center');

        return $this->renderJSON([
            'html'  =>  $content,
        ],'获取成功');
    }
}

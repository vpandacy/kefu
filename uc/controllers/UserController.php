<?php
namespace uc\controllers;

use common\components\helper\ValidateHelper;
use common\components\ValidateCode;
use common\models\uc\Merchant;
use common\models\uc\Staff;
use common\services\CaptchaService;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\uc\MerchantService;
use uc\controllers\common\BaseController;
use uc\services\UCUrlService;
use Yii;

/**
 * Default controller for the `merchant` module
 */
class UserController extends BaseController
{
    /**
     * 登录.
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

        $account = $this->post('account','');
        $password = $this->post('password','');
        $type = strpos($account,'@') > 0 ? 2 : 1;

        if($type == 2 && !ValidateHelper::validEmail($account)) {
            return $this->renderErrJSON('请输入正确的邮箱~~' );
        }

        if($type == 1 && !ValidateHelper::validMobile($account)) {
            return $this->renderErrJSON('请输入正确的手机号~~');
        }

        if(!$password) {
            return $this->renderErrJSON('请输入密码');
        }

        $query = Staff::find();

        if($type == 1) {
            $query->andWhere(['mobile'=>$account,'is_root'=>1,'status'=>ConstantService::$default_status_true]);
        }else{
            $query->andWhere([ 'email' => $account,'status' => ConstantService::$default_status_true ]);
        }

        $staff_info = $query->one();

        if( !$staff_info ) {
            return $this->renderErrJSON('登录失败，请检查邮箱或手机号和密码~~');
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

    /**
     * 注册.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionRegister()
    {
        $mobile = $this->post('mobile','');
        $merchant_name = $this->post('merchant_name','');
        $captcha = $this->post('captcha','');
        $img_captch = $this->post('img_captcha','');
        $password = $this->post('password','');

        if(!ValidateHelper::validMobile($mobile)) {
            return $this->renderErrJSON( '请输入正确的手机号~~' );
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

        $captcha_config = Yii::$app->params['cookies']['validate_code'];
        $source_code = $this->getCookie($captcha_config['name']);

        if($img_captch != $source_code) {
            return $this->renderErrJSON('请输入正确的图形验证码');
        }

        if(!CaptchaService::checkCaptcha($mobile, 1, $captcha)) {
            return $this->renderErrJSON('您输入的手机验证码不一致');
        }

        $merchant = Merchant::findOne(['name' => $merchant_name]);
        if($merchant) {
            return $this->renderErrJSON( '该商户名已经被使用了~~' );
        }

        if(!MerchantService::createMerchant($this->getAppId(), $merchant_name, $mobile, $password)){
            return $this->renderErrJSON( MerchantService::getLastErrorMsg() );
        }

        return $this->renderJSON( [], '创建成功,请登录商户~~' );
    }

    /**
     * 获取图形验证码.
     */
    public function actionCaptcha()
    {
        $this->layout = false;
        $captcha_config = Yii::$app->params['cookies']['validate_code'];

        $font_path = Yii::$app->getBasePath() . '/web/fonts/captcha.ttf';

        $captcha = new ValidateCode($font_path);

        Yii::$app->response->headers->add('Content-Type','image/png');
        Yii::$app->response->format = 'raw';

        $captcha->doimg();
        $this->setCookie($captcha_config['name'],$captcha->getCode(), 0, $captcha_config['domain']);
        return Yii::$app->end();
    }

    /**
     * 获取手机验证码.
     */
    public function actionGetCaptcha()
    {
        $mobile = $this->post('mobile','');

        if(!$mobile || !ValidateHelper::validMobile($mobile)) {
            return $this->renderErrJSON('请输入正确的手机号');
        }

        $code = $this->post('code', '');

        if(!$code) {
            return $this->renderErrJSON('请输入图形验证码');
        }

        $captcha_config = Yii::$app->params['cookies']['validate_code'];
        $source_code = $this->getCookie($captcha_config['name']);

        if($code != $source_code) {
            return $this->renderErrJSON('请输入正确的图形验证码');
        }

        // 这里要获取上一次的手机验证码.
        if(!CaptchaService::geneCustomCaptcha($mobile, 1)) {
            return $this->renderErrJSON(CaptchaService::getLastErrorMsg());
        }

        return $this->renderJSON([],'发送成功');
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

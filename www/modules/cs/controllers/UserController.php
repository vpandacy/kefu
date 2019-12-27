<?php
namespace www\modules\cs\controllers;

use common\components\helper\ValidateHelper;
use common\components\ValidateCode;
use common\models\uc\Merchant;
use common\models\uc\Staff;
use common\services\CaptchaService;
use common\services\uc\CustomerService;
use www\modules\cs\controllers\common\BaseController;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\uc\MerchantService;
use Yii;

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
                //获取商户信息
                return $this->redirect(GlobalUrlService::buildKFCSUrl('/'));
            }

            $this->layout = 'main';
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
            $query->andWhere(['mobile'=>$account, 'status'=>ConstantService::$default_status_true]);
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
        $token = $this->createLoginStatus( $staff_info );

        // 登录成功则认为可以接待游客.
        $staff_info['login_token']  = $token;

        if(!$staff_info->save(0)) {
            return $this->renderErrJSON('数据保存失败，请联系管理员');
        }

        return $this->renderJSON([
            "url" => $url ?? GlobalUrlService::buildKFUrl('/cs')
        ],'登录成功~~~~' );
    }

    /**
     * 注册.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionReg()
    {
        $mobile = $this->post('account','');
        $merchant_name = $this->post('merchant_name','');
        $captcha = $this->post('captcha','');
        $img_captcha = $this->post('img_captcha','');
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

        $captcha_config = \Yii::$app->params['cookies']['validate_code'];
        $source_code = $this->getCookie($captcha_config['name']);

        if(strtolower($img_captcha) != $source_code) {
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
     * 退出登录操作.
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $cookie = \Yii::$app->params['cookies']['staff'];

        if($this->current_user) {
            // 更新在线的状态.如果是退出登录了.
            Staff::updateAll([
                'is_online'=>ConstantService::$default_status_false],['id'=>$this->current_user['id']]);
        }

        $this->removeCookie($cookie['name'],$cookie['domain']);

        $url = GlobalUrlService::buildKFCSUrl('/user/login');

        if($this->isAjax()) {
            return $this->renderJSON(['redirect'=>$url],'退出成功');
        }

        return $this->redirect($url);
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

        $captcha_config = \Yii::$app->params['cookies']['validate_code'];
        $source_code = $this->getCookie($captcha_config['name']);

        if(strtolower($code) != $source_code) {
            return $this->renderErrJSON('请输入正确的图形验证码');
        }

        // 这里要获取上一次的手机验证码.
        if(!CaptchaService::geneCustomCaptcha($mobile, 1)) {
            return $this->renderErrJSON(CaptchaService::getLastErrorMsg());
        }

        return $this->renderJSON([],'发送成功');
    }

    /**
     * 获取当前在线的所有的客服.
     */
    public function actionOnline()
    {
        $cs = Staff::find()
            ->where([
                'merchant_id'   =>  $this->getMerchantId(),
                'status'        =>  ConstantService::$default_status_true,
                'is_online'     =>  ConstantService::$default_status_true
            ])
            ->asArray()
            ->select(['id','sn','name'])
            ->all();

        return $this->renderJSON($cs,'获取成功');
    }

    /**
     * 客服下线操作.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionOffline()
    {
        CustomerService::updateOnlineStatus($this->current_user['sn'], ConstantService::$default_status_false);

        return $this->renderJSON([],'操作成功');
    }

    /**
     * 客服上线操作
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionDoOnline()
    {
        CustomerService::updateOnlineStatus($this->current_user['sn'], ConstantService::$default_status_true);

        return $this->renderJSON([],'操作成功');
    }
}
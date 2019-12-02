<?php

namespace www\modules\merchant\controllers\common;

use common\components\BaseWebController;
use common\models\Employees;
use common\models\Merchants;
use common\services\GlobalUrlService;
use Yii;
use yii\base\Action;

class BaseController extends BaseWebController {
    public $merchant_info ;
    public $employee;

    public $merchant_cookie_name = 'chat_merchant_cookie';

    protected  $allowAllAction = [
        'merchant/user/login',
        'merchant/user/sign-in',
        'merchant/user/register',
    ];

    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $view = Yii::$app->view;
        $view->params['id'] = $id;
        $this->layout = "main";
    }

    /**
     * 检查权限.
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if(in_array($action->getUniqueId(), $this->allowAllAction)) {
            return true;
        }

        if(!$this->checkLoginStatus()) {
            // 设置跳转.
            $this->redirect(GlobalUrlService::buildMerchantUrl('/user/login',[
                'redirect_uri'  =>  GlobalUrlService::buildWwwUrl('/' . $action->getUniqueId())
            ]));
            return false;
        }

        Yii::$app->view->params['merchant'] = $this->merchant_info;
        Yii::$app->view->params['employee'] = $this->employee;
        return true;
    }

    /**
     * 开始商户登录权限.
     */
    protected function checkLoginStatus()
    {
        $auth_cookie = $this->getCookie($this->merchant_cookie_name,'');
        // 这里稍微注意下.
        @list($employee_id, $verify_token) = explode('#', $auth_cookie);

        // 一个都没有.那就验证失败.
        if(!$employee_id || !$verify_token) {
            return false;
        }

        $employee = Employees::findOne(['id'=>$employee_id, 'status'=>0]);

        if(!$employee || !$this->checkToken($verify_token, $employee)) {
            return false;
        }

        // 保存信息.
        $this->employee = $employee->toArray();

        $merchant = Merchants::findOne(['id'=>$employee['merchant_id'],'status'=>0]);
        if(!$merchant) {
            return false;
        }

        $this->merchant_info = $merchant->toArray();

        return true;
    }

    /**
     * 创建登录的状态.
     * @param $employee
     */
    public function createLoginStatus($employee)
    {
        $token = $employee['id'] . '#' . $this->genToken($employee['merchant_id'], $employee['salt'], $employee['password']);
        // 指定区域.
        $this->setCookie($this->merchant_cookie_name, $token,0,'','/merchant');
    }

    /**
     * 验证token.
     * @param $token
     * @param $employee
     * @return bool
     */
    protected function checkToken($token, $employee)
    {
        return $token == $this->genToken($employee['merchant_id'], $employee['salt'], $employee['password']);
    }

    /**
     * 生成登录令牌.
     * @param $merchant_id
     * @param $employee_salt
     * @param $password
     * @return string
     */
    protected function genToken($merchant_id, $employee_salt, $password)
    {
        return md5($employee_salt . md5($merchant_id . $password));
    }

    /**
     * 生成密码.
     * @param $merchant_id
     * @param $password
     * @param $salt
     * @return string
     */
    protected function genPassword($merchant_id,$password,$salt)
    {
        return md5($merchant_id . '-' . $password  . '-' . $salt);
    }

    /**
     * 获取商户ID.
     * @return int
     */
    public function getMerchantId()
    {
        return !$this->merchant_info ? $this->merchant_info['id'] : 0;
    }
}
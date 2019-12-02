<?php

namespace www\modules\merchant\controllers\common;

use common\components\BaseWebController;
use common\models\Merchants;
use common\services\GlobalUrlService;
use Yii;
use yii\base\Action;

class BaseController extends BaseWebController {
    public $merchant_info ;

    public $merchant_cookie_name = 'chat_merchant_cookie';

    protected  $allowAllAction = [
        "merchant/user/login"
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
                'redirect_url'  =>  GlobalUrlService::buildWwwUrl($action->getUniqueId())
            ]));
            return false;
        }

        return true;
    }

    /**
     * 开始商户登录权限.
     */
    protected function checkLoginStatus()
    {
        $auth_cookie = $this->getCookie($this->merchant_cookie_name,'');

        list($merchant_id, $verify_token) = explode('#', $auth_cookie);

        // 一个都没有.那就验证失败.
        if(!$merchant_id || !$verify_token) {
            return false;
        }

        $merchant = Merchants::findOne(['id'=>$merchant_id, 'status'=>0]);

        if(!$merchant) {
            return false;
        }

        // 开始处理登录.
        if(!$this->checkToken($verify_token, $merchant)) {
            return false;
        }

        // 保存信息.
        $this->merchant_info = $merchant->toArray();

        return true;
    }

    /**
     * 验证token.
     * @param $token
     * @param $merchant
     * @return bool
     */
    protected function checkToken($token, $merchant)
    {
        return $token == $this->genToken($merchant['id'], $merchant['salt'], $merchant['password']);
    }

    /**
     * 生成登录令牌.
     * @param $merchant_id
     * @param $merchant_salt
     * @param $password
     * @return string
     */
    protected function genToken($merchant_id, $merchant_salt, $password)
    {
        return md5($merchant_salt . md5($merchant_id . $password));
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
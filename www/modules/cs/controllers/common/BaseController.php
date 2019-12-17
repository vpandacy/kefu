<?php
namespace www\modules\cs\controllers\common;

use common\components\StaffBaseController;
use common\models\uc\Action;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\uc\MerchantService;
use Yii;

class BaseController extends StaffBaseController
{
    protected $allow_actions = [
        'cs/user/login',
        'cs/user/reg',
        'cs/user/get-captcha',
        'cs/user/captcha',
        'cs/default/forbidden',
    ];

    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config);
        $this->setTitle("客服聊天端");
        $this->layout = 'main';
    }

    /**
     * 过滤
     * @param Action $action
     * @return bool
     * @throws \Exception
     */
    public function beforeAction($action)
    {
        // 定义自己的应用ID.
        $app_id = ConstantService::$merchant_app_id;
        $this->setAppId( $app_id );
        Yii::$app->view->params['app_id'] = $this->getAppId();
        $is_login = $this->checkLoginStatus();
        if( in_array($action->getUniqueId(), $this->allow_actions )) {
            return true;
        }

        if (!$is_login) {
            if (\Yii::$app->request->isAjax) {
                $this->renderJSON([ 'url' => GlobalUrlService::buildKFCSUrl("/user/login") ], "未登录,请返回用户中心", -302);
            } else {
                $this->redirect(GlobalUrlService::buildKFCSUrl("/user/login"));
            }
            return false;
        }

        GlobalUrlService::setAppId($this->getAppId());

        //获取商户信息
        $this->merchant_info = MerchantService::checkValid( $this->current_user['merchant_id'] );
        if( !$this->merchant_info ){
            $this->redirect(GlobalUrlService::buildKFCSUrl("/default/forbidden", [ 'msg' => MerchantService::getLastErrorMsg() ]));
            return false;
        }

        // 商户信息.
        Yii::$app->view->params['merchant'] = $this->merchant_info;
        // 员工信息.
        Yii::$app->view->params['current_user'] = $this->current_user;
        return true;
    }
}
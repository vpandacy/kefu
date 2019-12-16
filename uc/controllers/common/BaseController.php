<?php

namespace uc\controllers\common;

use common\components\StaffBaseController;
use common\services\applog\AppLogService;
use common\services\AppService;
use common\services\GlobalUrlService;
use common\services\uc\MenuService;
use common\services\uc\MerchantService;
use yii\base\Action;
use yii\web\Response;
use Yii;

class BaseController extends StaffBaseController {

    private $allow_actions = [
        'user/login',
        'user/sign-in',
        'user/register',
        'user/logout',
        'user/captcha',
        '/default/application'
    ];

    //这些URL不需要检验权限
    public $ignore_url = [];

    public function __construct($id, $module, $config = [])  {
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }

    /**
     * @param Action $action
     * @return bool
     * @throws \Exception
     */
    public function beforeAction($action)
    {
        $app_name = $this->get('app_name','');
        if($app_name) {
            $this->setAppId( AppService::getAppId($app_name) );
        }

        GlobalUrlService::setAppId($this->getAppId());

        if(in_array($action->getUniqueId(), $this->allow_actions )) {
            return true;
        }

        $is_login = $this->checkLoginStatus();

        if (!$is_login) {
            if (\Yii::$app->request->isAjax) {
                $this->renderJSON([ 'url' => GlobalUrlService::buildUCUrl('/user/login') ], '未登录,请返回用户中心', -302);
            } else {
                $this->redirect(GlobalUrlService::buildUCUrl('/user/login'));
            }
            return false;
        }

        //获取商户信息
        $this->merchant_info = MerchantService::checkValid( $this->current_user['merchant_id'] );
        if( !$this->merchant_info ){
            $this->redirect(GlobalUrlService::buildUCUrl('/default/forbidden', [ 'url' => MerchantService::getLastErrorMsg() ]));
            return false;
        }

        if( !$this->checkPrivilege( $action->getUniqueId() ) ) {
            if(\Yii::$app->request->isAjax){
                $this->renderJSON([],'您无权访问此页面，请返回',-302);
            }else{
                $this->redirect( GlobalUrlService::buildUCUrl('/default/forbidden',[ 'url' => $action->getUniqueId()]) );
            }
            return false;
        }

        Yii::$app->view->params['menus'] = MenuService::getAllMenu($this->getAppId(), $this->privilege_urls);
        // 商户信息.
        Yii::$app->view->params['merchant'] = $this->merchant_info;
        // 员工信息.
        Yii::$app->view->params['current_user'] = $this->current_user;
        AppLogService::addAccessLog($this->current_user);
        return true;
    }
}
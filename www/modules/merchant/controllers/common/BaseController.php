<?php
namespace www\modules\merchant\controllers\common;

use common\components\StaffBaseController;
use common\services\applog\AppLogService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\uc\MenuService;
use common\services\uc\MerchantService;
use yii\web\Response;
use Yii;

class BaseController extends StaffBaseController
{
    private $allow_actions = [
        'merchant/default/forbidden',
        'merchant/default/index',
    ];
    //这些URL不需要检验权限
    public $ignore_url = [];

    public function __construct($id, $module, $config = [])  {
        parent::__construct($id, $module, $config = []);
        $this->layout = "main";
    }

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
                $this->renderJSON([ "url" => GlobalUrlService::buildUcUrl("/user/login") ], "未登录,请返回用户中心", -302);
            } else {
                $this->redirect(GlobalUrlService::buildUCUrl("/user/login"));
            }
            return false;
        }

        GlobalUrlService::setAppId($this->getAppId());

        //判断是否有访问该系统的权限，根据当前人登录的app_id判断
        $own_appids = $this->current_user->getAppIds();
        if( !in_array(  $this->app_id ,$own_appids ) ){
            $this->redirect( GlobalUrlService::buildUCUrl("/default/application") );
            return false;
        }

        //获取商户信息
        $this->merchant_info = MerchantService::checkValid( $this->current_user['merchant_id'] );
        if( !$this->merchant_info ){
            $this->redirect(GlobalUrlService::buildKFUrl("/default/forbidden", [ 'url' => MerchantService::getLastErrorMsg() ]));
            return false;
        }
        if( !$this->checkPrivilege( $action->getUniqueId() ) ) {
            if(\Yii::$app->request->isAjax){
                $this->renderJSON([],"您无权访问此页面，请返回",-302);
            }else{
                $this->redirect( GlobalUrlService::buildKFMerchantUrl("/default/forbidden",[ 'url' => $action->getUniqueId()]) );
            }
            return false;
        }
        // 这里要获取商户系统的菜单.
        Yii::$app->view->params['menus'] = MenuService::getAllMenu( $this->getAppId(), $this->privilege_urls);
        // 商户信息.
        Yii::$app->view->params['merchant'] = $this->merchant_info;
        // 员工信息.
        Yii::$app->view->params['current_user'] = $this->current_user;

        AppLogService::addAccessLog($this->current_user);
        return true;
    }
}
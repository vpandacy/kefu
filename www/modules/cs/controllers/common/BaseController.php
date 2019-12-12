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
    protected $allow_actions = [];

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
                $this->renderJSON([ 'url' => GlobalUrlService::buildUCUrl("/user/login") ], "未登录,请返回用户中心", -302);
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
                $this->redirect( GlobalUrlService::buildUcUrl("/default/forbidden",[ 'url' => $action->getUniqueId()]) );
            }
            return false;
        }
        // 商户信息.
        Yii::$app->view->params['merchant'] = $this->merchant_info;
        // 员工信息.
        Yii::$app->view->params['current_user'] = $this->current_user;
        return true;
    }
}
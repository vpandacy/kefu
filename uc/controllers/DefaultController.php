<?php
namespace uc\controllers;

use common\services\GlobalUrlService;
use uc\controllers\common\BaseController;
use common\services\ConstantService;
use uc\services\UCUrlService;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        return $this->redirect( UCUrlService::buildUCUrl("/default/application") );
    }

    public function actionApplication(){
        $own_appids = $this->current_user->getAppIds();
        if( count( $own_appids ) == 1 ){
            //客服系统
            if( in_array( ConstantService::$merchant_app_id ,$own_appids ) ){
                return $this->redirect(GlobalUrlService::buildKFMerchantUrl("/default/index"));
            }
            //admin系统
            if( in_array( ConstantService::$admin_app_id ,$own_appids ) ){
                return $this->redirect(GlobalUrlService::buildKFAdminUrl("/default/index"));
            }
        }
        $this->layout = false;
        return $this->render("application", $own_appids);
    }

    public function actionForbidden()
    {
        $this->layout = false;
        return $this->render('forbidden');
    }
    /**
     * 菜单栏右上角
     */
    public function actionMenu(){
        $content = $this->renderPartial("menu",[ "current_user" => $this->current_user]);
        return $this->renderJSON([ "content" => $content ]);
    }
}

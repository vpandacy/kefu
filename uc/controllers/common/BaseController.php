<?php

namespace uc\controllers\common;

use common\components\StaffBaseController;
use common\services\AppService;
use common\services\GlobalUrlService;
use common\services\uc\MenuService;
use common\services\uc\MerchantService;
use yii\web\Response;
use Yii;

class BaseController extends StaffBaseController {
    private $allow_actions = [
        'user/login',
        'user/sign-in',
        'user/register',
        'user/logout',
    ];
    //这些URL不需要检验权限
    public $ignore_url = [];

    public function __construct($id, $module, $config = [])  {
        parent::__construct($id, $module, $config = []);
        $this->layout = "main";
    }

    public function beforeAction($action)
    {
        $app_name = $this->get('app_name','');
        if($app_name) {
            $this->setAppId( AppService::getAppId($app_name) );
        }
        Yii::$app->view->params['app_name'] = $app_name;
        Yii::$app->view->params['app_id'] = $this->getAppId();
        if(in_array($action->getUniqueId(), $this->allow_actions )) {
            return true;
        }

        $is_login = $this->checkLoginStatus();
        if (!$is_login) {
            if (\Yii::$app->request->isAjax) {
                $this->renderJSON([ "url" => GlobalUrlService::buildUCUrl("/user/login") ], "未登录,请返回用户中心", -302);
            } else {
                $this->redirect(GlobalUrlService::buildUCUrl("/user/login"));
            }
            return false;
        }

        //获取商户信息
        $this->merchant_info = MerchantService::checkValid( $this->current_user['merchant_id'] );
        if( !$this->merchant_info ){
            $this->redirect(GlobalUrlService::buildWwwUrl("/default/forbidden", [ 'url' => MerchantService::getLastErrorMsg() ]));
            return false;
        }

        if( !$this->checkPrivilege( $action->getUniqueId() ) ) {
            if(\Yii::$app->request->isAjax){
                $this->renderJSON([],"您无权访问此页面，请返回",-302);
            }else{
                $this->redirect( GlobalUrlService::buildLZUrl("/default/forbidden",[ 'url' => $action->getUniqueId()]) );
            }
            return false;
        }

        Yii::$app->view->params['menus'] = MenuService::getAllMenu($this->getAppId(), $this->privilege_urls);
        return true;
    }

    /**
     * 渲染分页的界面.
     * @param array $data
     * @param string $msg
     * @param int $count
     * @return \yii\console\Response|Response
     */
    public function renderPageJSON($data = [], $msg = '', $count = 0)
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data   = [
            'msg'    => $msg,
            'code'   => 0,
            'data'   => $data,
            'count'  => $count,
            'req_id' => $this->geneReqId()
        ];

        return $response;
    }
}
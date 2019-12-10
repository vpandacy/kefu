<?php
namespace www\modules\merchant\controllers\common;

use common\components\StaffBaseController;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\uc\MenuService;
use common\services\uc\MerchantService;
use yii\web\Response;
use Yii;

class BaseController extends StaffBaseController
{
    private $allow_actions = [];
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
        Yii::$app->view->params['app_name'] = "";
        Yii::$app->view->params['app_id'] = $this->getAppId();
        $is_login = $this->checkLoginStatus();
        if(in_array($action->getUniqueId(), $this->allow_actions )) {
            return true;
        }

        if (!$is_login) {
            if (\Yii::$app->request->isAjax) {
                $this->renderJSON([ "url" => GlobalUrlService::buildUCUrl("/user/login") ], "未登录,请返回用户中心", -302);
            } else {
                $this->redirect(GlobalUrlService::buildUCUrl("/user/login"));
            }
            return false;
        }

        //判断是否有访问该系统的权限，根据当前人登录的app_id判断
        $own_appids = $this->current_user->getAppIds();
        if( !in_array(  $this->app_id ,$own_appids ) ){
            $this->redirect( GlobalUrlService::buildUCUrl("/default/application") );
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
        // 这里要获取商户系统的菜单.
        Yii::$app->view->params['menus'] =  MenuService::getMerchantUrl($this->privilege_urls);

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
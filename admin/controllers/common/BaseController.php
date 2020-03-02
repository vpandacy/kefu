<?php
namespace admin\controllers\common;

use common\components\StaffBaseController;
use common\services\AppMenuService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\uc\MenuService;
use yii\base\Action;
use Yii;

class BaseController extends StaffBaseController
{
    protected $allow_actions = [

    ];

    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config);
        $this->setTitle("Admin端");
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
        $app_id = ConstantService::$admin_app_id;
        $this->setAppId( $app_id );
        Yii::$app->view->params['app_id'] = $this->getAppId();
        $is_login = $this->checkLoginStatus();
        if( in_array($action->getUniqueId(), $this->allow_actions )) {
            return true;
        }
        if (!$is_login) {
            if (\Yii::$app->request->isAjax) {
                $this->renderJSON([ 'url' => GlobalUrlService::buildUcUrl("/user/login") ], "未登录,请返回用户中心", -302);
            } else {
                $this->redirect(GlobalUrlService::buildUcUrl("/user/login"));
            }
            return false;
        }

        GlobalUrlService::setAppId($this->getAppId());
        \Yii::$app->view->params['app_type'] = $this->getAppId();
        // 员工信息.
        Yii::$app->view->params['current_user'] = $this->current_user;
        //组合菜单
        \Yii::$app->view->params['menus'] = $this->getMenu();
        return true;
    }

    /**
     * 获取所有菜单.
     * @return array
     */
    private function getMenu()
    {
        $menus = MenuService::getAllMenu($this->getAppId());
        foreach ($menus as $key => &$_menu ){
            //如果强制设置了不显示，那就不要在判断
            if( isset( $_menu['hidden']) ){
                continue;
            }

            $prefix = '';

            $tmp_counter = count( $_menu['sub'] );
            foreach ($_menu['sub'] as $_key => &$_menu_sub ){
                if( isset( $_menu_sub['hidden']) && $_menu_sub['hidden']  ){
                    $tmp_counter = $tmp_counter - 1;
                    continue;
                }

                if( !$this->checkPrivilege( $prefix.$_menu_sub['url'] ) ){
                    $_menu_sub['hidden'] = true;
                    $tmp_counter = $tmp_counter - 1;
                }
            }

            $_menu['hidden'] = ($tmp_counter <= 0 );

        }
        return $menus;
    }
}
<?php
namespace www\modules\merchant\controllers\common;

use common\components\StaffBaseController;
use common\services\ConstantService;
use common\services\uc\MenuService;
use yii\web\Response;
use Yii;

class BaseController extends StaffBaseController
{
    public function beforeAction($action)
    {
        // 定义自己的应用ID.
        $this->setAppId(ConstantService::$merchant_app_id);

        if(!parent::beforeAction($action)) {
            return false;
        }

        // 这里要获取商户系统的菜单.
        Yii::$app->view->params['menus'] =  MenuService::getMerchantUrl($this->privilege_urls);
        $this->layout = 'main';
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
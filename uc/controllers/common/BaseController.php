<?php

namespace uc\controllers\common;

use common\components\StaffBaseController;
use common\services\uc\MenuService;
use yii\web\Response;
use Yii;

class BaseController extends StaffBaseController {

    public function beforeAction($action)
    {
        if(!parent::beforeAction($action)){
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
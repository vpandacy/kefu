<?php
namespace www\modules\merchant\controllers\common;

use common\components\StaffBaseController;
use www\modules\merchant\service\MenuService;
use yii\web\Response;
use Yii;

class BaseController extends StaffBaseController
{
    public function beforeAction($action)
    {
        if(!parent::beforeAction($action)) {
            return false;
        }

        // 这里要获取商户系统的菜单.
        Yii::$app->view->params['menus'] =  MenuService::getAllMenu($this->privilege_urls, $this->staff['is_root']);
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
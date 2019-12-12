<?php
namespace www\controllers\common;

use common\components\BaseWebController;
use common\services\CommonService;
use Yii;

class BaseController extends BaseWebController {

    protected $allowAllAction = []; //在这里面的就不用检查合法性

    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $view = Yii::$app->view;
        $view->params['id'] = $id;
    }

    public function beforeAction($action)
    {
        return true;
    }


	protected  function renderJS($msg,$url = "/")
    {
		return $this->renderPartial("@www/views/layouts/js",['msg' => $msg,'location' => $url ]);
	}

    protected function geneReqId()
    {
        return uniqid();
    }

    /**
     * 获取游客用户的uuid.
     */
    public function getGuestUUID()
    {
        $cookies = Yii::$app->params['cookies']['guest'];

        $uuid = $this->getCookie($cookies['name']);

        if(!$uuid) {
            $uuid = CommonService::genUUID();
            $this->setCookie($cookies['name'], $uuid, strtotime('+10 year') - time(), $cookies['domain']);
        }

        return $uuid;
    }
}



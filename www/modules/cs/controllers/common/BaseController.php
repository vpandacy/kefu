<?php
namespace www\modules\cs\controllers\common;

use common\components\StaffBaseController;
use common\services\ConstantService;

class BaseController extends StaffBaseController
{

    public function beforeAction($action)
    {
        // 定义自己的应用ID.
        $this->setAppId(ConstantService::$merchant_app_id);

        if(!parent::beforeAction($action)) {
            return false;
        }

        $this->layout = 'main';
        return true;
    }
}
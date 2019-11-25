<?php

namespace www\modules\cs\controllers\common;

use common\components\BaseWebController;
use Yii;

class BaseController extends BaseWebController {
    public $current_user  ;
    public $merchant_info ;
    protected  $allowAllAction = [
        "cs/user/login"
    ];

    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $view = Yii::$app->view;
        $view->params['id'] = $id;
        $this->layout = false;
    }

}
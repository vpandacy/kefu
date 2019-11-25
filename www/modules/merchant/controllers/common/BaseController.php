<?php

namespace www\modules\merchant\controllers\common;

use common\components\BaseWebController;
use Yii;

class BaseController extends BaseWebController {
    public $current_user  ;
    public $merchant_info ;
    protected  $allowAllAction = [
        "merchant/user/login"
    ];

    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $view = Yii::$app->view;
        $view->params['id'] = $id;
        $this->layout = "main";
    }

}
<?php

namespace www\modules\cs\controllers;

use common\services\monitor\WSCenterService;
use www\modules\cs\controllers\common\BaseController;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        $current_info = $this->current_user;
        $data = [
            "staff" => $this->current_user,
            "js_params" => [
                "ws" => WSCenterService::getCSWSByRoute( $current_info['id'] ),
                "sn" => $current_info['sn'],
                "msn" => $this->merchant_info['sn'],
            ]
        ];
        return $this->render('index', $data );
    }
}

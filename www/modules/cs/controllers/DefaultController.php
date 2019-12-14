<?php

namespace www\modules\cs\controllers;

use common\models\merchant\CommonWord;
use common\services\ConstantService;
use common\services\monitor\WSCenterService;
use www\modules\cs\controllers\common\BaseController;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        $current_info = $this->current_user;

        // 要获取常用语信息.
        $words = CommonWord::find()
            ->where([
                'merchant_id'   =>  $current_info['merchant_id'],
                'status'        =>  ConstantService::$default_status_true,
            ])
            ->asArray()
            ->select(['id','words'])
            ->all();

        return $this->render('index', [
            'staff' => $this->current_user,
            'words' =>  $words,
            'js_params' => [
                'ws' => WSCenterService::getCSWSByRoute( $current_info['id'] ),
                'sn' => $current_info['sn'],
                'msn' => $this->merchant_info['sn'],
            ]
        ] );
    }
}

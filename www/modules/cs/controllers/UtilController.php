<?php

namespace www\modules\cs\controllers;

use common\models\merchant\CommonWord;
use common\services\ConstantService;
use www\modules\cs\controllers\common\BaseController;

class UtilController extends BaseController {

    public function actionGetWord(){
        $merchant_id = $this->getMerchantId();
        // 要获取常用语信息.
        $word_list = CommonWord::find()->select(['id',"title",'words'])
            ->where([
                'merchant_id'   =>  $merchant_id,
                'status'  =>  ConstantService::$default_status_true,
            ])->asArray()->all();

        return $this->renderJSON( $word_list );
    }
}

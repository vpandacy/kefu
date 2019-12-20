<?php

namespace www\modules\cs\controllers;

use common\models\merchant\CommonWord;
use common\models\uc\Staff;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\monitor\WSCenterService;
use www\modules\cs\controllers\common\BaseController;

class DefaultController extends BaseController
{
    /**
     * 默认进入即上线.
     * @return string
     */
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

        $staff = Staff::findOne(['id'=>$this->current_user['id']]);
        $staff['is_online'] = 1;

        if($staff->save() === false) {
            return $this->redirect(GlobalUrlService::buildUcUrl('/default/forbidden'));
        }

        return $this->render('index', [
            'staff' => $this->current_user,
            'words' =>  $words,
            'js_params' => [
                'ws' => WSCenterService::getCSWSByRoute( $current_info['id'] ),
                'sn' => $current_info['sn'],
                'msn'=> $this->merchant_info['sn'],
            ]
        ]);
    }

    public function actionForbidden()
    {
        $this->layout = false;
        return $this->render('forbidden');
    }
}

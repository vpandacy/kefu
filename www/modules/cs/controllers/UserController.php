<?php

namespace www\modules\cs\controllers;


use common\models\uc\Staff;
use common\services\uc\CustomerService;
use www\modules\cs\controllers\common\BaseController;
use common\services\ConstantService;

class UserController extends BaseController
{
    /**
     * 获取当前在线的所有的客服.
     * 方便转移客服
     */
    public function actionOnline()
    {
        $cs = Staff::find()
            ->where([
                'merchant_id' => $this->getMerchantId(),
                'status' => ConstantService::$default_status_true,
                'is_online' => ConstantService::$default_status_true
            ])
            ->asArray()
            ->select(['id', 'sn', 'name'])
            ->all();

        return $this->renderJSON($cs, '获取成功');
    }

    /**
     * 客服下线操作.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionOffline()
    {
        CustomerService::updateOnlineStatus($this->current_user['sn'], ConstantService::$default_status_false);

        return $this->renderJSON([], '操作成功');
    }

    /**
     * 客服上线操作
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionDoOnline()
    {
        CustomerService::updateOnlineStatus($this->current_user['sn'], ConstantService::$default_status_true);

        return $this->renderJSON([], '操作成功');
    }
}
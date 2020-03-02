<?php
namespace admin\controllers;

use admin\controllers\common\BaseController;
use common\models\uc\MonitorKfWs;

class SettingController extends BaseController
{
    /**
     * ws配置信息.
     */
    public function actionWs()
    {
        $monitor = MonitorKfWs::find()
            ->asArray()
            ->all();

        return $this->render('ws',[
            'monitor'   =>  $monitor
        ]);
    }
}
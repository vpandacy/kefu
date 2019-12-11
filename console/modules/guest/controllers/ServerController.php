<?php

namespace console\modules\guest\controllers;

use console\controllers\BaseController;
use console\services\GateWayWorkerService;
use console\services\GuestBusiHanlderService;
use Workerman\Worker;

class ServerController extends BaseController
{
    /**
     * 注册中心，先启动
     * php yii guest/server/reg
     */
    public function actionReg(){
        $config = \Yii::$app->params['guest'];
        GateWayWorkerService::runRegister( $config['register']);
    }

    /**
     * php yii guest/server/gateway
     */
    public function actionGateway(){
        $config = \Yii::$app->params['guest'];
        GateWayWorkerService::runGateway( $config['gateway'] );
    }

    /**
     * php yii guest/server/busi-worker
     */
    public function actionBusiWorker(){
        $config = \Yii::$app->params['guest'];
        $params = $config['busi_worker'];
        $params['handler'] = GuestBusiHanlderService::class;
        GateWayWorkerService::runBusiWorker( $params );
    }

    /**
     * php yii guest/server/run-all
     */
    public function actionRunAll(){
        $this->actionReg();
        $this->actionGateway();
        $this->actionBusiWorker();
        // 运行所有服务
        Worker::runAll();
    }
}

<?php

namespace console\modules\cs\controllers;
use console\controllers\BaseController;
use console\services\CSBusiHanlderService;
use console\services\GateWayWorkerService;
use Workerman\Worker;

class ServerController extends BaseController
{
    /**
     * 注册中心，先启动
     * php yii cs/server/reg
     */
    public function actionReg(){
        $config = \Yii::$app->params['cs'];
        GateWayWorkerService::runRegister( $config['register']);
    }

    /**
     * php yii cs/server/gateway
     * 这里要多启动一个内部端口，专门用来接受数据
     */
    public function actionGateway(){
        $config = \Yii::$app->params['cs'];
        GateWayWorkerService::runGateway( $config['gateway'] );
    }

    /**
     * php yii cs/server/busi-worker
     */
    public function actionBusiWorker(){
        $config = \Yii::$app->params['cs'];
        $params = $config['busi_worker'];
        $params['handler'] = CSBusiHanlderService::class;
        GateWayWorkerService::runBusiWorker( $params );
    }

    /**
     * php yii cs/server/run-all
     */
    public function actionRunAll(){
        // 标记是全局启动
        define('GLOBAL_START', 1); // 获取全局的变量.
        GateWayWorkerService::initParams();
        $this->actionReg();
        $this->actionGateway();
        $this->actionBusiWorker();
        // 运行所有服务
        Worker::runAll();
    }
}

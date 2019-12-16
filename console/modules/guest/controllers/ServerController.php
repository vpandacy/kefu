<?php

namespace console\modules\guest\controllers;

use console\controllers\BaseController;
use console\services\GateWayWorkerService;
use console\services\GuestBusiHanlderService;
use Workerman\Worker;

class ServerController extends BaseController
{
    /**
     * 由于本身启动就是根据 命令行参数来的
     * 本身 是 php a.php start | stop 等
     * 而yii 是这样的 php yii xxx  start。
     * 由于为了支付更多网关和工作进程，增加了参数游标
     * php bi xxx start idx(1、2、3)
     *
     */
    public function __construct($id, $module, $config = [])
    {
        global $argc;
        global $argv;
        // 需要自动缩减所在参数. 不然会影响workerman的命令解析.
        $argc = $argc - 1;
        array_shift($argv);

        parent::__construct($id, $module, $config);
    }


    /**
     * 注册中心，先启动
     * php yii guest/server/reg
     */
    public function actionReg( $act = 'start' , $guest_idx = 1 ){
        $config = \Yii::$app->params[ "guest_{$guest_idx}" ];
        $params = $config['register'];
        $params['name'] = "{$params['name']}_{$guest_idx}";
        GateWayWorkerService::runRegister( $params );
    }

    /**
     * php yii guest/server/gateway
     */
    public function actionGateway( $act = 'start' , $guest_idx = 1,$gateway_idx = 1 ){
        $config = \Yii::$app->params[ "guest_{$guest_idx}" ];
        $params = $config[ "gateway_{$gateway_idx}"];
        $params['name'] = "{$params['name']}_{$guest_idx}_{$gateway_idx}";
        GateWayWorkerService::runGateway( $params );
    }

    /**
     * php yii guest/server/busi-worker
     */
    public function actionBusiWorker( $act = 'start' ,$guest_idx = 1,$busiworker_idx = 1 ){
        $config = \Yii::$app->params[ "guest_{$guest_idx}" ];
        $params = $config[ "busi_worker_{$busiworker_idx}" ];
        $params['handler'] = GuestBusiHanlderService::class;
        $params['name'] = "{$params['name']}_{$guest_idx}_{$busiworker_idx}";
        GateWayWorkerService::runBusiWorker( $params );
    }

    /**
     * php yii guest/server/run-all start 1
     */
    public function actionRunAll( $act = 'start' ,$guest_idx = 1 ){
        //计算有几个gateway 和 busiworker
        $config = \Yii::$app->params[ "guest_{$guest_idx}" ];
        $gateway_idxs = $busiworker_idxs = [];
        foreach ( $config as $_key => $_item ){
            if( mb_stripos( $_key,"gateway_" ) !== false ){
                $gateway_idxs[] = str_replace( "gateway_" ,"",$_key );
            }

            if( mb_stripos( $_key,"busi_worker_" ) !== false ){
                $busiworker_idxs[] = str_replace( "busi_worker_" ,"",$_key );
            }
        }

        // 标记是全局启动
        define('GLOBAL_START', 1); // 获取全局的变量.
        GateWayWorkerService::initParams();
        $this->actionReg( $act, $guest_idx );
        foreach ( $gateway_idxs as $_gateway_idx ) {
            $this->actionGateway($act, $guest_idx,$_gateway_idx);
        }

        foreach ( $busiworker_idxs as $_busiworker_idx ){
            $this->actionBusiWorker($act, $guest_idx,$_busiworker_idx );
        }
        // 运行所有服务
        Worker::runAll();
    }
}

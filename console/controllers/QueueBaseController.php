<?php
namespace console\controllers;

use common\services\QueueListService;
use console\controllers\BaseController;

abstract class QueueBaseController extends BaseController {

    protected $instance_name = null;
    protected $queue_name = "empty_queue_name";

    protected $fifo = true;
    protected $maxTask = 100;

    protected $taskId = "";
    protected abstract function handle($data) ;


    public function actionStart() {

        try {
            for($i = 1; $i<= $this->maxTask; ++$i) {
                //todo get queue data
                $data = QueueListService::shift( $this->instance_name,$this->queue_name );
                if($data) {
                    $this->taskId = uniqid();
                    $this->log('start');
                    $delay = isset($data['created_time'])?time() - $data['created_time']:"unknown";

                    if( $this->handle($data['data'])) {
                        $this->log("handle task success,task delay:{$delay}s");
                    } else {
                        $this->errorLog("handle task fail,task delay:{$delay}s",1);
                    }
                } else {
                    $this->log("cleared" , false);
                    return true;
                }

            }
            $this->log("done {$this->maxTask} tasks");

        } catch ( \Exception $e) {
            $this->errorLog($e->getMessage(), 0);
            //在抛出一个错误，为了可以在错误控制器中记录到ops平台呀
            throw new \Exception( $e );
        }
    }


    protected function errorLog($str, $error_level = 1) {
        $level = ($error_level) ? "[warning]" :"[fatal error]";
        $this->log($level . $str);
    }

    protected function log($str, $fileLog = true) {
        global $argv;
        $date = '['.date("Y-m-d H:i:s")."]";
        $str = $date."[{$this->taskId}]".$str;

        if( $fileLog && !empty($argv[2]) ) {
            $logFileName = $argv[2].$this->queueName."-".date("Y_m").".log";
            error_log("$str\n", 3, $logFileName);
        }
        echo "{$date}[{$argv[1]}]$str\n";
    }


}
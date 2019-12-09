<?php
namespace console\modules\chat\controllers;

use console\modules\chat\BaseController;
use console\modules\chat\service\WorkerService;

class CustomerServiceController extends BaseController
{
    /**
     * 客服端ws处理.
     */
    public function actionRun()
    {
        if(strpos(strtolower(PHP_OS), 'win') === 0)  {
            exit("start.php not support windows, please use start_for_win.bat\n");
        }

        // 检查扩展
        if(!extension_loaded('pcntl') )  {
            exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
        }

        if(!extension_loaded('posix'))  {
            exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
        }

        WorkerService::runCustomerService();
    }
}
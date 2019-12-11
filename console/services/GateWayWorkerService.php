<?php
namespace console\services;
use common\services\BaseService;
use GatewayWorker\Register;
use GatewayWorker\Gateway;
use GatewayWorker\BusinessWorker;
use Workerman\Worker;

class GateWayWorkerService extends BaseService
{

    public static function runRegister( $params = [] ){
        self::initParams();
        // register 必须是text协议
        $register = new Register("text://{$params['host']}");
        $register->name = $params['name'];
        // 如果不是在根目录启动，则运行runAll方法
        if( !defined('GLOBAL_START') )  {
            Worker::runAll();
        }
    }

    public static function runGateway( $params = [] ){
        self::initParams();

        //ws协议
        $gateway = new Gateway("Websocket://{$params['host']}");
        // gateway名称，status方便查看
        $gateway->name = $params['name'];
        // gateway进程数
        $gateway->count = 4;
        // 本机ip，分布式部署时使用内网ip
        $gateway->lanIp = '127.0.0.1';
        // 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
        // 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口
        $gateway->startPort = 4000;
        // 服务注册地址
        $gateway->registerAddress = $params['register_host'];
        //心跳间隔
        $gateway->pingInterval = 15;
        //客户端连续$pingNotResponseLimit次$pingInterval时间内不回应心跳则断开链接
        $gateway->pingNotResponseLimit = 2;
        //心跳数据
        $gateway->pingData = '{"cmd":"ping"}';
        // http://doc3.workerman.net/640187  透过nginx/apache代理如何获取客户端真实ip ?
        $gateway->onConnect = function($connection) {
            $connection->onWebSocketConnect = function($connection , $http_header)  {
                if( isset( $_SERVER['HTTP_X_REAL_IP'] ) ){
                    $_SESSION['REMOTE_IP'] = $_SERVER['HTTP_X_REAL_IP'];
                }
            };
        };
        // 如果不是在根目录启动，则运行runAll方法
        if( !defined('GLOBAL_START') )  {
            Worker::runAll();
        }
    }

    public static function runBusiWorker( $params = [] ){
        self::initParams();

        // business worker 进程
        $business_worker = new BusinessWorker();
        // worker名称
        $business_worker->name = $params['name'];
        // business worker进程数量
        $business_worker->count = 4;
        // 服务注册地址
        $business_worker->registerAddress = $params['register_host'];

        //设置处理业务的类为MyEvent
        $business_worker->eventHandler = $params['handler'];

        // 设置业务超时时间10秒
        $business_worker->processTimeout = 3;
        // 业务超时回调，可以把超时日志保存到自己想要的地方
        $business_worker->processTimeoutHandler = function($trace_str, $exeption) {
            //file_put_contents('/your/path/process_timeout.log', $trace_str, FILE_APPEND);
            //错误信息丢进redis，redis 通过job 获取写入数据库
            // 返回假，让进程重启，避免进程继续无限阻塞
            return false;
        };


        // 监听一个text端口,我觉得还是用http协议 相对来说比较重
//        $inner_http_worker = new Worker( 'text://0.0.0.0:2223' );
//        $inner_http_worker->name = 'customer_text';
//        // 当http客户端发来数据时触发
//        $inner_http_worker->onMessage = function( $connection, $data){
//            $message = json_decode( $data,true );
//            return $connection->send( "success" );
//        };

        // 如果不是在根目录启动，则运行runAll方法
        if( !defined('GLOBAL_START') )  {
            Worker::runAll();
        }
    }

    /**
     * 初始化参数,以修复workerman的解析
     */
    private static function initParams()
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
        if( defined( 'GLOBAL_START') ){
            return;
        }

        // 标记是全局启动
        define('GLOBAL_START', 1);
        // 获取全局的变量.
        global $argc;
        global $argv;
        // 需要自动缩减所在参数. 不然会影响workerman的命令解析.
        $argc = $argc - 1;
        array_shift($argv);


        $runtime = \Yii::$app->getRuntimePath() . '/workerman';

        if(!is_dir($runtime)) {
            mkdir($runtime,0777, true);
        }


        $params = $argv;

        $params = array_slice($params, 0, count($params) -  1);

        $pid_file = str_replace(['-',' '],'',implode('_', $params));
        $pid_file = str_replace('/','',$pid_file);
        $start_file = $pid_file . '_start_file';
        touch($runtime . '/' . $start_file);
        // 存取对应的pid.
        $argv[0] = $start_file;
        Worker::$pidFile = $runtime . '/' . $pid_file . '.pid';
    }
}
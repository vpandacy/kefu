<?php
namespace console\modules\chat\service;

use common\services\BaseService;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Workerman\Worker;

class WorkerService extends BaseService
{
    /**
     * 注册游客端的服务. 服务可能要改一下.
     */
    public static function runCustomer()
    {
        self::initParams();
        // register 必须是text协议
        $register = new Register('text://0.0.0.0:1238');
        $register->name = 'customer_register';

        // gateway 进程，这里使用Text协议，可以用telnet测试
        $gateway = new Gateway("Websocket://0.0.0.0:8282");
        // gateway名称，status方便查看
        $gateway->name = 'customer_gateway';
        // gateway进程数
        $gateway->count = 4;
        // 本机ip，分布式部署时使用内网ip
        $gateway->lanIp = '127.0.0.1';
        // 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
        // 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口
        $gateway->startPort = 4000;
        // 服务注册地址
        $gateway->registerAddress = '127.0.0.1:1238';
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

        // business worker 进程
        $business_worker = new BusinessWorker();
        // worker名称
        $business_worker->name = 'customer_worker';
        // business worker进程数量
        $business_worker->count = 4;
        // 服务注册地址
        $business_worker->registerAddress = '127.0.0.1:1238';

        //设置处理业务的类为MyEvent
        $business_worker->eventHandler = CustomerBusinessService::class;

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
        $inner_http_worker = new Worker( 'text://0.0.0.0:2223' );
        $inner_http_worker->name = 'customer_text';
        // 当http客户端发来数据时触发
        $inner_http_worker->onMessage = function( $connection, $data){
            $message = json_decode( $data,true );
            return $connection->send( "success" );
        };

        Worker::runAll();
    }

    /**
     * 初始化参数,以修复workerman的解析
     */
    private static function initParams()
    {
        $runtime = \Yii::$app->getRuntimePath() . '/workerman';

        if(!is_dir($runtime)) {
            mkdir($runtime);
        }

        // 标记是全局启动
        define('GLOBAL_START', 1);
        // 获取全局的变量.
        global $argc;
        global $argv;
        // 需要自动缩减所在参数. 不然会影响workerman的命令解析.
        $argc = $argc - 1;
        array_shift($argv);
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

    /**
     * 这里是注册客服端的地址.
     */
    public static function runCustomerService()
    {
        self::initParams();
        // register 必须是text协议
        $register = new Register('text://0.0.0.0:1328');
        $register->name = 'customer_service_register';

        // gateway 进程，这里使用Text协议，可以用telnet测试
        $gateway = new Gateway("Websocket://0.0.0.0:9191");
        // gateway名称，status方便查看
        $gateway->name = 'customer_service_gateway';
        // gateway进程数
        $gateway->count = 4;
        // 本机ip，分布式部署时使用内网ip
        $gateway->lanIp = '127.0.0.1';
        // 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
        // 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口
        $gateway->startPort = 4000;
        // 服务注册地址
        $gateway->registerAddress = '127.0.0.1:1328';
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

        // business worker 进程
        $business_worker = new BusinessWorker();
        // worker名称
        $business_worker->name = 'customer_service_worker';
        // business worker进程数量
        $business_worker->count = 4;
        // 服务注册地址
        $business_worker->registerAddress = '127.0.0.1:1328';

        //设置处理业务的类为MyEvent
        $business_worker->eventHandler = CustomerService::class;

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
        $inner_http_worker = new Worker( 'text://0.0.0.0:2223' );
        $inner_http_worker->name = 'customer_text';
        // 当http客户端发来数据时触发
        $inner_http_worker->onMessage = function( $connection, $data){
            $message = json_decode( $data,true );
            return $connection->send( "success" );
        };

        Worker::runAll();
    }
}
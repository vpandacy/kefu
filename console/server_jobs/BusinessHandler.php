<?php
/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */

//declare(ticks=1);
use GatewayWorker\Lib\Gateway;
/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */


class BusinessHandler{

    /**
     * 这里应该要启动一个text监控，然后将微信客服系统的数据通过tcp传递过来
     * 然后通过这里转发给对应的客服人员
     */
    public static function onWorkerStart(  $worker ){

    }

	/**
	 * 当客户端连接时触发
	 * 如果业务不需此回调可以删除onConnect
	 * @param int $client_id 连接id
	 */
	public static function onConnect( $client_id ){
	}

	/**
	 * 当客户端发来消息时触发
	 * @param int $client_id 连接id
	 * @param mixed $message 具体消息
	 */
	public static function onMessage($client_id, $message) {
		// 这里是向单个人发送. 及时回复. 先别发送.
        Gateway::sendToClient( $client_id, $message );
        return;
		$message = json_decode($message, true);
		if( isset( $_SESSION['REMOTE_IP'] ) && isset( $_SERVER['REMOTE_ADDR'] ) ){
            $_SERVER['REMOTE_ADDR'] = $_SESSION['REMOTE_IP'];
        }
		$message = $message + $_SERVER;
	}

	/**
	 * 当用户断开连接时触发
	 * @param int $client_id 连接id
	 */
	public static function onClose($client_id){
		// 向所有人发送
	}
}
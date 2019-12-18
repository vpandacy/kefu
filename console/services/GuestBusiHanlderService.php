<?php

namespace console\services;

use common\components\helper\DateHelper;
use common\services\BaseService;
use common\services\chat\ChatEventService;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\QueueListService;
use GatewayWorker\Lib\Gateway;
use Workerman\Worker;

class GuestBusiHanlderService extends BaseService
{
    /**
     * 这里应该要启动一个text监控，然后将微信客服系统的数据通过tcp传递过来
     * 然后通过这里转发给对应的客服人员
     */
    public static function onWorkerStart($worker)
    {
//如果不特殊处理，就会启动多次，而一个端口被占用，在此启动就会报错
        if( $worker->id != 0 ){
            return;
        }
        //再启动一个text协议，用来进行数据转发,
        try{
            $params_inner = $worker->transfer_params;
            if( $params_inner ){
                $inner_worker = new Worker( "text://{$params_inner['host']}" );
                $inner_worker->name = $params_inner['name'];
                $inner_worker->onMessage = function( $connection, $data){
                    try{
                        $message = json_decode( $data,true );
                        self::consoleLog( var_export( $message,true ) );
                        if( isset( $message['data']['t_id'])){
                            //发送给对应的人
                            $tmp_client = Gateway::getClientIdByUid( $message['data']['t_id'] );
                            // 发送事件给游客
                            $tmp_client && Gateway::sendToClient( $tmp_client[0], $data );
                        }

                        // 主动断开链接. 客服关闭后.
                        if(ConstantService::$chat_cmd_close_guest == $message['cmd']) {
                            // 发送给对应的人
                            $tmp_client = Gateway::getClientIdByUid( $message['data']['t_id'] );
                            // 关闭信息.
                            $tmp_client && Gateway::closeClient( $tmp_client[0] );
                        }

                        return $connection->send( "success" );
                    }catch (\Exception $e){
                        ChatEventService::handlerError( $e->getTraceAsString() );
                    }
                };
                // 运行worker
                $inner_worker->listen();
            }
        }catch (\Exception $e){
            ChatEventService::handlerError( $e->getTraceAsString() );
        }
    }

    /**
     * 当游客端浏览器连接时触发
     * 如果业务不需此回调可以删除onConnect
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
    }

    /**
     * 当游客端浏览器发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     * $message = [
     *      "cmd" => "动作",
     *      "data" => "内容"
     * ];
     */
    public static function onMessage($client_id, $message)
    {
        try{
            $message = @json_decode($message, true);
            $message = $message??[];
            if (isset($_SESSION['REMOTE_IP']) && isset($_SERVER['REMOTE_ADDR'])) {
                $_SERVER['REMOTE_ADDR'] = $_SESSION['REMOTE_IP'];
            }
            $message = $message + $_SERVER;
            // @todo 后面考虑将返回信息给统一返回.不然太麻烦了.就返回信息就得写很多.
            $data = $message['data'] ?? [];
            $f_id = $data['f_id'] ?? 0;
            Worker::log( var_export( $message,true ) );
            switch ($message['cmd']) {
                case ConstantService::$chat_cmd_guest_in://客户进来到页面，设置绑定关系，使用 Gateway::bindUid(string $client_id, mixed $uid);
                    if ($f_id) {
                        //建立绑定关系，后面就可以根据f_id找到这个人了
                        Gateway::bindUid($client_id, $f_id);
                        $cache_params = [
                            "uuid" => $f_id,
                            "msn" => $data['msn']??''
                        ];
                        ChatEventService::setGuestBindCache( $client_id ,$cache_params);
                        //后面的输入应该进入redis，存起来，为了后续分析
                    }
                    $params = [
                        "content" => time()
                    ];
                    $ws_data = ChatEventService::buildMsg( ConstantService::$chat_cmd_hello,$params );
                    Gateway::sendToClient( $client_id,$ws_data );
                    //需要同时将消息转发一份到对话队列，然后存储起来
                    QueueListService::push2ChatDB( QueueConstant::$queue_chat_log,$message );
                    break;
                case ConstantService::$chat_cmd_guest_connect://客户链接,要分配客服
                    $code = $data['code'] ?? '';
                    $kf_info = ChatEventService::getKFByRoute( $data['msn'] , $code);
                    if( $kf_info ){
                        $params = [
                            "sn" => $kf_info['sn'],
                            "name" => $kf_info['name'],
                            "avatar" => GlobalUrlService::buildPicStaticUrl("hsh",$kf_info['avatar'])
                        ];
                        $data = ChatEventService::buildMsg( ConstantService::$chat_cmd_assign_kf,$params );
                        //转发消息给对应的客服，做好接待准备
                        $transfer_params = [
                            "f_id" => $f_id,
                            "t_id" => $kf_info['sn'],
                            // 随机生成一个昵称.
                            'nickname'  =>  'Guest-' . substr($f_id, strlen($f_id) - 12),
                            'avatar'    =>  GlobalUrlService::buildPicStaticUrl('hsh',ConstantService::$default_avatar),
                            'allocation_time'   =>  date('H:i:s'),
                        ];
                        $transfer_data = ChatEventService::buildMsg( ConstantService::$chat_cmd_guest_connect,$transfer_params );
                        QueueListService::push2CS( QueueConstant::$queue_cs_chat,json_decode($transfer_data,true) );

                        //同时换成起来对应的客服信息
                        $cache_params = ChatEventService::getGuestBindCache( $client_id);
                        $cache_params['kf_id'] = $kf_info['id'];
                        $cache_params['kf_sn'] = $kf_info['sn'];
                        ChatEventService::setGuestBindCache( $client_id ,$cache_params);
                    }else{
                        $params = [
                            'content'   => ChatEventService::getLastErrorMsg(),
                            'code'      =>  ConstantService::$response_code_fail
                        ];
                        $data = ChatEventService::buildMsg( ConstantService::$chat_cmd_system,$params );
                    }
                    Gateway::sendToClient( $client_id,$data );
                    //找找目前有咩有空闲的客服，找一个在线的客服分配过去
                    break;
                case ConstantService::$chat_cmd_chat: // 游客聊天动作.
                    //将消息转发给另一个WS服务组，放入redis，然后通过Job搬运
                    QueueListService::push2CS( QueueConstant::$queue_cs_chat,$message);
                    break;
                case ConstantService::$chat_cmd_pong:
                    break;
                case ConstantService::$chat_cmd_ping:
                    Gateway::sendToClient($client_id,json_encode(['cmd'=>ConstantService::$chat_cmd_pong]));
                    break;
            };
        }catch (\Exception $e){
            ChatEventService::handlerError( $e->getTraceAsString() );
        }
    }

    /**
     * 当游客端用户断开连接时触发
     * @param int $client_id 连接id
     * 客户端关闭了，需要通知客服，但是client_id 对应的客服是谁，我们不知道，
     * 所以需要通过client_id 找到 对应的客服（可以去表中查下）
     */
    public static function onClose($client_id)
    {
        $cache_params = ChatEventService::getGuestBindCache( $client_id);
        ChatEventService::clearGuestBindCache( $client_id );
        $close_params = [
            "client_id" => $client_id,
            "closed_time" => DateHelper::getFormatDateTime()
        ];
        $close_params = array_merge( $close_params,$cache_params );
        $close_data = ChatEventService::buildMsg( ConstantService::$chat_cmd_guest_close,$close_params );
        Worker::log( $close_data );
        QueueListService::push2CS( QueueConstant::$queue_cs_chat,json_decode($close_data,true) );
    }
}
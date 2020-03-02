<?php

namespace console\services;

use common\components\helper\DateHelper;
use common\services\BaseService;
use common\services\chat\ChatEventService;
use common\services\chat\ChatGroupService;
use common\services\chat\GuestService;
use common\services\CommonService;
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
                        self::consoleLog(var_export($message,true));
                        if( isset( $message['data']['t_id'])){
                            //发送给对应的人
                            $tmp_client = Gateway::getClientIdByUid( $message['data']['t_id'] );

                            if(!$tmp_client) {
                                return;
                            }

                            //同时换成起来对应的客服信息
                            $cache_params = ChatEventService::getGuestBindCache( $tmp_client[0] );

                            if($message['cmd'] == ConstantService::$chat_cmd_guest_connect) {
                                $data['source'] = $cache_params['source'];
                                $data['media'] = $cache_params['media'];
                            }

                            // 发送事件给游客
                            Gateway::sendToClient( $tmp_client[0], $data );
                        }

                        GuestBusiHanlderService::handleInnerMessage($message);
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
            $message = $message ? $message : [];
            if (isset($_SESSION['REMOTE_IP']) && isset($_SERVER['REMOTE_ADDR'])) {
                $_SERVER['REMOTE_ADDR'] = $_SESSION['REMOTE_IP'];
            }
            $message = $message + $_SERVER;
            Worker::log( var_export( $message,true ) );
            self::handleMessage($client_id, $message);
        }catch (\Exception $e){
            ChatEventService::handlerError( $e->getTraceAsString() );
        }
    }

    /**
     * 当游客端用户断开连接时触发
     * @param int $client_id 连接id
     * 客户端关闭了，需要通知客服，但是client_id 对应的客服是谁，我们不知道，
     * 所以需要通过client_id 找到 对应的客服（可以去表中查下）
     * @return bool
     */
    public static function onClose($client_id)
    {
        $cache_params = ChatEventService::getGuestBindCache( $client_id );
        ChatEventService::clearGuestBindCache( $client_id );

        $uuid = isset($cache_params['uuid']) ? $cache_params['uuid'] : '';

        $old_client_ids = Gateway::getClientIdByUid($uuid);

        // 不需要处理. 如果存在.就证明有其他的客户端在使用.
        if($old_client_ids) {
            return false;
        }

        // 加上转发消息.
        $close_params = [
            'client_id'     => $client_id,
            'closed_time'   => DateHelper::getFormatDateTime(),
            't_id'          => isset($cache_params['kf_sn']) ? $cache_params['kf_sn'] : '',
            'f_id'          => $uuid
        ];

        $close_params = array_merge( $close_params, $cache_params );
        $close_data = ChatEventService::buildMsg( ConstantService::$chat_cmd_guest_close, $close_params );
        Worker::log( $close_data );
        QueueListService::push2ChatDB( QueueConstant::$queue_chat_log, json_decode($close_data, true) );
        QueueListService::push2CS( QueueConstant::$queue_cs_chat, json_decode($close_data,true) );
        // 关闭内容.
        ChatEventService::clearGuestBindCache($uuid);
        // 解绑.同时还要处理其他动作.移除在线状态和离线状态.
        if(in_array($uuid, ChatGroupService::getWaitGroupAllUsers($close_params['t_id']))) {
            ChatGroupService::leaveGroup($close_params['t_id'], $uuid);
        }

        if(in_array($uuid, ChatGroupService::getWaitGroupAllUsers($close_params['t_id']))) {
            ChatGroupService::leaveWaitGroup($close_params['t_id'], $uuid);
        }
        return Gateway::unbindUid($client_id, $uuid);
    }

    /**
     * 游客消息处理.
     * @param $client_id
     * @param $message
     * @return true
     */
    public static function handleMessage($client_id, $message)
    {
        switch ($message['cmd']) {
            case ConstantService::$chat_cmd_guest_in://客户进来到页面，设置绑定关系，使用 Gateway::bindUid(string $client_id, mixed $uid);
                self::handleGuestIn($client_id, $message);
                break;
            case ConstantService::$chat_cmd_guest_connect://客户链接,要分配客服
                //找找目前有咩有空闲的客服，找一个在线的客服分配过去
                self::handleGuestConnect($client_id,$message);
                break;
            case ConstantService::$chat_cmd_chat: // 游客聊天动作.
                //将消息转发给另一个WS服务组，放入redis，然后通过Job搬运.如果在聊天的队列中.就允许发送.
                if(!isset($message['data']['t_id'])) {
                    return Gateway::sendToClient($client_id,ChatEventService::buildMsg(ConstantService::$chat_cmd_system,[
                        'content'   =>  '请稍等，客服正在到来中...',
                    ]));
                }
                if(ChatGroupService::checkUserInGroup($message['data']['t_id'],$message['data']['f_id'])) {
                    return QueueListService::push2CS(QueueConstant::$queue_cs_chat, $message);
                }

                Gateway::sendToClient($client_id,ChatEventService::buildMsg(ConstantService::$chat_cmd_system,[
                    'content'   =>  '请稍等，客服正在到来中...',
                ]));
                break;
            case ConstantService::$chat_cmd_pong:
                break;
            case ConstantService::$chat_cmd_ping:
                Gateway::sendToClient($client_id,ChatEventService::buildMsg(ConstantService::$chat_cmd_pong));
                break;
        };

        return true;
    }

    /**
     * 游客guest_in事件.
     * @param $client_id
     * @param $message
     */
    public static function handleGuestIn($client_id, $message)
    {
        $data = $message['data'] ?? [];
        $f_id = $data['f_id'] ?? 0;

        if ($f_id) {
            //建立绑定关系，后面就可以根据f_id找到这个人了
            $old_client_id = Gateway::getClientIdByUid($f_id);
            if($old_client_id) {
                Gateway::unbindUid($old_client_id[0],$f_id);
                // 关闭信息
                Gateway::sendToClient($old_client_id[0], ChatEventService::buildMsg(ConstantService::$chat_cmd_close_guest,[
                    't_id'  =>  $f_id,
                    'content'   =>  '您使用了新的窗口，已关闭'
                ]));

                Gateway::closeClient($old_client_id[0]);
            }
            // 解除绑定信息.
            $old_client_id && Gateway::unbindUid($old_client_id[0], $f_id);
            Gateway::bindUid($client_id, $f_id);
            $cache_params = [
                "uuid"  => $f_id,
                "msn"   => $data['msn'] ?? '',
                'source'=> CommonService::getSourceByUa($message['data']['ua']),    // 终端.
                'title' => $data['title'] ?? '',
                'media' =>  isset($message['data']['rf'])                           // 渠道.
                    ? GuestService::getRefererSidByUrl($data['rf'])
                    : 0,
                'code'  => $data['code'] ?? '',
            ];

            // 绑定两份. 可能会通过f_id查找对应的信息.
            ChatEventService::setGuestBindCache($f_id, $cache_params);
            ChatEventService::setGuestBindCache($client_id, $cache_params);
        }

        $ws_data = ChatEventService::buildMsg(ConstantService::$chat_cmd_hello, [
            'content' => time()
        ]);

        //需要同时将消息转发一份到对话队列，然后存储起来
        QueueListService::push2ChatDB( QueueConstant::$queue_chat_log, $message );
        Gateway::sendToClient( $client_id,$ws_data );
    }

    /**
     * 游客guest_connect事件.
     * @param $client_id
     * @param $message
     * @return void
     */
    public static function handleGuestConnect($client_id, $message)
    {
        $data = $message['data'] ?? [];
        $code = $data['code'] ?? '';
        $f_id = $data['f_id'] ?? 0;
        $kf_info = ChatEventService::getKFByRoute($f_id, $data['msn'] , $code, $message['REMOTE_ADDR']);

        if( !$kf_info ){
            $params = [
                'content'   => ChatEventService::getLastErrorMsg(),
                'code'      => ConstantService::$response_code_fail
            ];
            $data = ChatEventService::buildMsg( ConstantService::$chat_cmd_system,$params );
            Gateway::sendToClient( $client_id, $data );
            return;
        }

        // 这里是分配成功.
        if($kf_info['act'] == 'success') {
            self::assignCustomerServiceSuccess($f_id, $client_id, $kf_info);
        }else{
            self::assignCustomerServiceWait($f_id,$client_id,$kf_info);
        }
    }

    /**
     * 成功分配客服.
     * @param int $f_id
     * @param string $client_id
     * @param array $kf_info
     */
    public static function assignCustomerServiceSuccess($f_id, $client_id, $kf_info)
    {
        $data = ChatEventService::buildMsg( ConstantService::$chat_cmd_assign_kf,[
            "sn" => $kf_info['sn'],
            "name" => $kf_info['nickname'],
            "avatar" => GlobalUrlService::buildPicStaticUrl('hsh', $kf_info['avatar'] )
        ]);

        //同时换成起来对应的客服信息
        $cache_params = ChatEventService::getGuestBindCache( $client_id);


        //转发消息给对应的客服，做好接待准备
        $transfer_params = [
            "f_id" => $f_id,
            "t_id" => $kf_info['sn'],
            // 随机生成一个昵称.
            'nickname'  => substr($f_id, strlen($f_id) - 12),
            'avatar'    => GlobalUrlService::buildPicStaticUrl('hsh',ConstantService::$default_avatar),
            'allocation_time'  =>  date('H:i:s'),
            'source'    =>  $cache_params['source'],
            'media' =>  $cache_params['media'],
        ];

        $transfer_data = ChatEventService::buildMsg( ConstantService::$chat_cmd_guest_connect, $transfer_params );
        QueueListService::push2CS( QueueConstant::$queue_cs_chat,json_decode($transfer_data,true) );


        $cache_params['kf_id'] = $kf_info['id'];
        $cache_params['kf_sn'] = $kf_info['sn'];
        // 将client_id加入到这个组中.
        if(!ChatGroupService::checkUserInGroup($kf_info['sn'], $f_id)) {
            ChatGroupService::joinGroup($kf_info['sn'], $f_id);
        }
        ChatEventService::setGuestBindCache( $client_id ,$cache_params);
        Gateway::sendToClient( $client_id, $data );
    }

    /**
     * 已经分配客服.但还需等待.
     * @param int $f_id
     * @param string $client_id
     * @param array $kf_info
     */
    public static function assignCustomerServiceWait($f_id, $client_id, $kf_info)
    {
        $num = ChatGroupService::countUserInWaitGroup($kf_info['sn']);

        // 通知客户.
        $data = ChatEventService::buildMsg( ConstantService::$chat_cmd_assign_kf_wait, [
            "sn" => $kf_info['sn'],
            "name" => $kf_info['nickname'],
            "avatar" => GlobalUrlService::buildPicStaticUrl("hsh",$kf_info['avatar']),
            "wait_num" => $num + 1
        ]);

        //同时换成起来对应的客服信息
        $cache_params = ChatEventService::getGuestBindCache( $client_id );

        //转发消息给对应的客服，做好接待准备
        $transfer_params = [
            "f_id" => $f_id,
            "t_id" => $kf_info['sn'],
            // 随机生成一个昵称.
            'nickname'  =>  'Guest-' . substr($f_id, strlen($f_id) - 12),
            'avatar'    =>  GlobalUrlService::buildPicStaticUrl('hsh',ConstantService::$default_avatar),
            'allocation_time'   =>  date('H:i:s'),
            'source'    =>  isset($cache_params['source']) ? $cache_params['source'] : 0,
            'media' =>  isset($cache_params['media']) ? $cache_params['media'] : 0,
        ];

        $transfer_data = ChatEventService::buildMsg( ConstantService::$chat_cmd_guest_wait_connect,$transfer_params );

        $cache_params['kf_id'] = $kf_info['id'];
        $cache_params['kf_sn'] = $kf_info['sn'];
        if(!in_array($f_id, ChatGroupService::getWaitGroupAllUsers($kf_info['sn']))) {
            // 将client_id加入到这个组中.
            ChatGroupService::joinWaitGroup($kf_info['sn'], $f_id);
            QueueListService::push2CS( QueueConstant::$queue_cs_chat,json_decode($transfer_data,true));
        }
        ChatEventService::setGuestBindCache( $client_id ,$cache_params);
        Gateway::sendToClient( $client_id, $data );
    }

    /**
     * 处理内部的消息事件.
     * @param $message
     */
    public static function handleInnerMessage($message)
    {
        switch ($message['cmd']) {
            // 主动断开链接. 客服关闭后.
            case ConstantService::$chat_cmd_close_guest:
                // 发送给对应的人
                $tmp_client = Gateway::getClientIdByUid( $message['data']['t_id'] );
                // 关闭信息.
                $tmp_client && Gateway::closeClient( $tmp_client[0] );
                // 退出组信息.
                ChatGroupService::leaveGroup($message['data']['f_id'], $message['data']['t_id']);
                // 清除缓存...
                ChatEventService::clearGuestBindCache($message['data']['t_id']);
                $tmp_client && ChatEventService::clearCSBindCache($tmp_client[0]);
                break;
            // 给组内进行广播.
            case ConstantService::$chat_cmd_kf_logout:
                $mess = ChatEventService::buildMsg(ConstantService::$chat_cmd_kf_logout,[
                    'f_id'  =>  $message['data']['f_id'],
                ]);

                // 给组内进行广播.
                ChatGroupService::notifyGroupUserByGroupName($message['data']['f_id'], $mess);
                ChatGroupService::notifyWaitGroupUserByGroupName($message['data']['f_id'], $mess);
                ChatGroupService::removeWaitGroup($message['data']['f_id']);
                ChatGroupService::removeGroup($message['data']['f_id']);
                break;
            case ConstantService::$chat_cmd_change_kf:
                // 发送给对应的人
                $tmp_client = Gateway::getClientIdByUid( $message['data']['t_id'] );
                if($tmp_client) {
                    //　传递给新的组.
                    ChatGroupService::leaveGroup($message['data']['f_id'], $message['data']['t_id']);
                    ChatGroupService::joinGroup( $message['data']['cs']['sn'], $message['data']['t_id']);

                    //同时换成起来对应的客服信息
                    $cache_params = ChatEventService::getGuestBindCache( $tmp_client[0]);
                    $cache_params['kf_id'] = $message['data']['cs']['id'];
                    $cache_params['kf_sn'] = $message['data']['cs']['sn'];
                    ChatEventService::setGuestBindCache( $tmp_client[0] ,$cache_params);
                }
                break;
        }
    }
}
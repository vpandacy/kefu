<?php

namespace console\modules\chat\controllers\queue;


use common\services\chat\ChatSocketService;
use common\services\chat\GuestChatService;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
use common\services\QueueListService;
use common\services\uc\MerchantService;
use console\controllers\QueueBaseController;

/***
 * Class PushController
 * Author: Guo Wei
 * php yii chat/queue/chat/start
 * 主要来存储客户聊天数据的
 */
class ChatController extends QueueBaseController
{
    public function __construct($id, $module, $config = [])
    {
        $this->queue_name = QueueConstant::$queue_chat_log;
        $this->instance_name = QueueConstant::$instance_chat_log;
        parent::__construct($id, $module, $config);
    }

    protected function handle($data)
    {
        if ( !$data ) {
            return $this->echoLog("no data to handler");
        }

        $this->echoLog( var_export( $data ,true) );
        $cmd = $data['cmd'];
        $params_data = $data['data'];
        // 这里有其他的处理消息.
        switch ($cmd){
            case ConstantService::$chat_cmd_guest_in:
                $merchant_info = MerchantService::getInfoBySn( $params_data['msn'] );
                //游客进入
                $params = [
                    "merchant_id" => $merchant_info['id'],
                    "client_ua" => $params_data['ua'],
                    "referer_url" => $params_data['rf'],
                    "land_url" => $params_data['land'],
                    "uuid" => $params_data['f_id'],
                    "client_id" => $data['GATEWAY_CLIENT_ID'],
                    "client_ip" => $data['REMOTE_ADDR'],
                    "status" => ConstantService::$default_status_neg_1,
                    "closed_time" => ConstantService::$default_datetime
                ];
                GuestChatService::addGuest( $params );
                break;
            case ConstantService::$chat_cmd_guest_close://　游客关闭了ws
                $merchant_info = MerchantService::getInfoBySn( $params_data['msn'] );
                $params = [
                    "client_id" => $params_data['client_id'],
                    "closed_time" => $params_data['closed_time'],
                    "status" => ConstantService::$default_status_true,
                    "merchant_id" => $merchant_info['id'],
                    "uuid" => $params_data['uuid'],
                    "cs_id" =>  $params_data['kf_id'],
                ];
                GuestChatService::closeGuest( $params );
                break;
            case ConstantService::$chat_cmd_close_guest:// 客服主动关闭了．或者将游客信息给拉入黑名单中了.
                $merchant_info = MerchantService::getInfoBySn( $params_data['msn'] );
                $params = [
                    'closed_time'   =>  $params_data['closed_time'],
                    'status'        =>  ConstantService::$default_status_true,
                    'merchant_id'   =>  $merchant_info['id'],
                    'cs_id'         =>  $params_data['kf_id'],
                    'uuid'          =>  $params_data['uuid'],
                ];
                GuestChatService::closeGuest($params);
                break;
        }
        return $this->echoLog( "ok~~" );
    }

    public function actionTest(){
        $data = [
            "cmd" => "chant",
            "data" => [
                "content" => "你好"
            ]
        ];
        $this->handle( $data );
    }
}
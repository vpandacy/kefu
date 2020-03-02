<?php

namespace console\modules\chat\controllers;

use common\models\uc\Staff;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
use common\services\monitor\WSCenterService;
use common\services\QueueListService;
use common\services\redis\CacheService;
use common\services\worker\WorkerService;
use console\controllers\BaseController;
use uc\services\UCConstantService;

class MonitorController extends BaseController
{
    /**
     * php yii chat/monitor/scrapy
     * 获取网关，然后根据网关获取gateway 和 busi worker
     */
    public function actionScrapy()
    {
        $params = \Yii::$app->params;
        $guest_config = [];
        $cs_config = [];

        // 获取所有的注册中心.
        $all_register = [];

        foreach ($params as $_key => $_item ) {
            if (mb_stripos($_key, "guest_") !== false) {
                $guest_config[str_replace("guest_", '', $_key)] = $_item;
            }

            if (mb_stripos($_key, "cs_") !== false) {
                $cs_config[str_replace("cs_", '', $_key)] = $_item;
            }

            if(isset($_item['register'])) {
                $all_register[$_item['register']['ip'] . ':' .$_item['register']['port']] = 0;
            }
        }


        // 根据注册中心获取所有的节点信息.
        if(!count($all_register)) {
            return $this->stdout('暂无注册中心信息');
        }

//        foreach($all_register as $key => $value) {
//            $all_register[$key] = WorkerService::getGatewayClientByRegister($key);
//        }

        $register_mapping = [];
        $owner_group = 1;
        // 获取所有的guest_ws数据中心. 一组服务.根据一组服务来得到对应的地址和通讯端口.
        foreach ( $guest_config as $_key => $_item ) {
            // 删除内部监听端口.
            unset($_item['push']);

            if(isset($_item['register'])) {
                // 这里去添加注册中心信息.
                $params = [
                    'type'  =>  UCConstantService::$ws_register,
                    'name'  =>  $_item['register']['name'],
                    'ip'    =>  $_item['register']['ip'],
                    'port'  =>  $_item['register']['port'],
                    'start_port'    =>  $_item['register']['port'],
                    'owner_group'   =>  $owner_group,
                    'count' =>  1,
                ];

                $register_id = WSCenterService::setKFWS( $params );

                $register_mapping[$_item['register']['ip'] . ':' . $_item['register']['port']] = $register_id;

                unset($_item['register']);
            }

            $this->handleWorker($_item, $all_register, $register_mapping, $owner_group);
        }

        $owner_group = 0;
        foreach ( $cs_config as $_key => $_item ) {
            // 删除内部监听端口.
            unset($_item['push']);

            if(isset($_item['register'])) {
                // 这里去添加注册中心信息.
                $params = [
                    'type'  =>  UCConstantService::$ws_register,
                    'name'  =>  $_item['register']['name'],
                    'ip'    =>  $_item['register']['ip'],
                    'port'  =>  $_item['register']['port'],
                    'start_port'    =>  $_item['register']['port'],
                    'owner_group'   =>  $owner_group,
                    'count' =>  1,
                ];

                $register_id = WSCenterService::setKFWS( $params );

                $register_mapping[$_item['register']['ip'] . ':' . $_item['register']['port']] = $register_id;

                unset($_item['register']);
            }

            $this->handleWorker($_item, $all_register, $register_mapping);
        }


        return $this->echoLog( "ok" );
    }

    /**
     * 配置信息
     * @param array $config 配置信息
     * @param array $all_register 注册中心
     * @param array $register_mapping 所属注册中心
     * @param int $owner_group  所属组
     */
    public function handleWorker($config, $all_register, $register_mapping, $owner_group = 0) {
        // 开始保存gateway信息.
        $gateway_info = [];

        foreach($config as $key=>$service) {
            if(strpos($key,'gateway') !== false) {
                array_push($gateway_info, $service);
            }
        }

        // gateway 管理.
        foreach($gateway_info as $gateway) {
//            $gateway_process = array_filter($all_register[$gateway['register_host']], function ($ip) use ($gateway) {
//                $port = explode(':',$ip)[1];
//
//                return $port >= $gateway['start_port'] && $gateway['start_port'] + 4 <= $port;
//            });

            $params = [
                'type'  =>  UCConstantService::$ws_gateway,
                'name'  =>  $gateway['name'],
                'ip'    =>  $gateway['ip'],
                'port'  =>  $gateway['port'],
                'start_port'    =>  $gateway['start_port'],
                'owner_reg'     =>  isset($register_mapping[$gateway['register_host']])
                    ? $register_mapping[$gateway['register_host']]
                    : 0,
                'owner_group'   =>  $owner_group,
                'count' =>  4,
            ];

            WSCenterService::setKFWS( $params );
        }

        $business_worker = [];

        foreach($config as $key=>$service) {
            if(strpos($key,'busi_worker') !== false) {
                array_push($business_worker, $service);
            }
        }

        // 开始保存.
        foreach($business_worker as $bs) {
            $params = [
                'type'  =>  UCConstantService::$ws_busiworker,
                'name'  =>  $bs['name'],
                'port'  =>  0,
                'start_port'    =>  0,
                'owner_reg'     =>  isset($register_mapping[$bs['register_host']])
                    ? $register_mapping[$bs['register_host']]
                    : 0,
                'owner_group'   =>  $owner_group,
                'count' =>  0,
            ];

            WSCenterService::setKFWS( $params );
        }
    }

    /**
     * php yii chat/monitor/check-online-cs
     * 检查客服是否在线.
     */
    public function actionCheckOnlineCs()
    {
        // 获取所有的OnlineUsers.
        $online_users = Staff::find()
            ->asArray()
            ->select(['sn'])
            ->where([
                'is_online' =>  1
            ])
            ->all();

        if(!$online_users) {
            return $this->stdout("no online customer service\n");
        }

        foreach($online_users as $user) {
            // 开始批量获取查询.
            QueueListService::push2CS(QueueConstant::$queue_cs_chat, [
                'cmd'   =>  ConstantService::$chat_cmd_kf_health,
                'data'  =>  [
                    'sn'    =>  $user['sn']
                ],
            ]);
        }
        return $this->stdout("success");
    }
}
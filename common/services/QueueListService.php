<?php
namespace common\services;
use common\services\constant\QueueConstant;
use \common\services\redis\ListService;
use common\services\redis\RedisService;

class QueueListService extends BaseService
{
    /*
     * 推送到客服的redis
     * */
    public static function push2CS( $list_name = null,$value = [] ){
        if( !$list_name ){
            return false;
        }
        $redis = RedisService::getInstance( QueueConstant::$instance_cs );
        $value = [
            "data" => $value,
            "created_time" => time()
        ];
        return $redis->rPush($list_name, serialize($value));
    }

    public static function push2Guest( $list_name = null ,$value = []){
        if( !$list_name ){
            return false;
        }
        $redis = RedisService::getInstance( QueueConstant::$instance_quest );
        $value = [
            "data" => $value,
            "created_time" => time()
        ];
        return $redis->rPush($list_name, serialize($value));
    }

    public static function shift( $instance_name = null,$list_name = null ){
        $return = RedisService::getInstance($instance_name)->rPop($list_name);
        if (!$return) {
            return $return;
        }
        return @unserialize($return);
    }
}
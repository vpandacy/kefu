<?php
namespace common\services;
use common\services\constant\QueueConstant;
use common\services\redis\RedisService;

class QueueListService extends BaseService
{
    /**
     * 推送到客服的redis
     * @param string $list_name 队列名
     * @param array $value 值.
     * @return bool
     */
    public static function push2CS( $list_name = null,$value = [] )
    {
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

    /**
     * 推送到游客的redis中.
     * @param string $list_name 队列名
     * @param array $value 值.
     * @return bool
     */
    public static function push2Guest( $list_name = null ,$value = [])
    {
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

    public static function push2ChatDB( $list_name = null ,$value = [] ){
        if( !$list_name ){
            return false;
        }

        $redis = RedisService::getInstance( QueueConstant::$instance_chat_log );

        $value = [
            "data" => $value,
            "created_time" => time()
        ];
        return $redis->rPush($list_name, serialize($value));
    }
    /**
     * 从redis队列中取出一个.
     * @param string|null $instance_name
     * @param string|null $list_name
     * @return mixed
     */
    public static function shift( $instance_name = null,$list_name = null )
    {
        $return = RedisService::getInstance($instance_name)->rPop($list_name);
        if (!$return) {
            return $return;
        }
        return @unserialize($return);
    }
}
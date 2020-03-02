<?php
namespace common\services\redis;


class ListService
{
    /**
     * [$redis should be a common\components\RedisConnection object]
     */
    public static $redis = null;

    private static function _get_redis($list_name)
    {
        //TODO 根据list_name的 用途 分配不同的redis 实例
        if (self::$redis === null) {
            self::$redis = \Yii::$app->list_001;
        }
        return self::$redis;
    }

    public static function len($list_name)
    {
        return self::_get_redis($list_name)->lLen($list_name);
    }

    public static function shift($list_name)
    {
        $return = self::_get_redis($list_name)->rPop($list_name);
        if (!$return) {
            return $return;
        }
        return @unserialize($return);
    }

    public static function unshift($list_name, $value)
    {
        $value = [
            "data" => $value,
            "created_time" => time()
        ];
        return self::_get_redis($list_name)->lPush($list_name, serialize($value));
    }

    public static function clear($list_name)
    {
        return self::_get_redis($list_name)->del($list_name);
    }

    public static function push($list_name, $value)
    {
        $value = [
            "data" => $value,
            "created_time" => time()
        ];
        return self::_get_redis($list_name)->rPush($list_name, serialize($value));
    }

    public static function pop($list_name)
    {
        $return = self::_get_redis($list_name)->rPop($list_name);
        if (!$return) {
            return $return;
        }
        return @unserialize($return);
    }
}
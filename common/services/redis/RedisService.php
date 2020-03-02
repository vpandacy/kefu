<?php
namespace common\services\redis;


class RedisService
{

    public static $redis = null;

    public static function getInstance( $instance_name = null ){
        if( !isset( self::$redis[ $instance_name] ) ){
            self::$redis[ $instance_name] =  \Yii::$app->get( $instance_name );
        }
        return self::$redis[ $instance_name];
    }
}
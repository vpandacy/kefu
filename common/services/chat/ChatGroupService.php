<?php
namespace common\services\chat;

use common\services\BaseService;
use common\services\redis\CacheService;
use GatewayWorker\Lib\Gateway;

/**
 * 游客分组操作.
 * Class ChatGroupService
 * @package common\services\chat
 */
class ChatGroupService extends BaseService
{

    private static $group_prefix = 'group_';

    private static $wait_group_prefix = 'wait_';

    /**
     * 检查用户是否在聊天组中.
     * @param $group_name
     * @param $uuid
     * @return bool
     */
    public static function checkUserInGroup($group_name, $uuid)
    {
        $all_users = self::getGroupAllUsers($group_name);

        return in_array($uuid, $all_users);
    }

    /**
     * 游客加入组.
     * @param $group_name
     * @param $uuid
     * @return bool
     */
    public static function joinGroup($group_name, $uuid)
    {
        $cache_key = self::$group_prefix . $group_name;
        $group_data = self::getGroupAllUsers( $group_name );
        $group = $group_data ? $group_data : [];
        if( self::checkUserInGroup($group_name, $uuid) ){
            return true;
        }

        var_dump( "--------{$group_name} join start----------" );
        array_push($group, $uuid);
        var_dump( $group );
        var_dump( "--------{$group_name} join end----------" );
        // 将组加入到缓存中.
        return CacheService::set($cache_key, @serialize( $group ), 86400 * 30);
    }

    /**
     * 离开组.
     * @param $group_name
     * @param $uuid
     * @return bool
     */
    public static function leaveGroup($group_name, $uuid)
    {
        var_dump( "--------{$group_name} leave start----------" );
        var_dump( $uuid );
        $cache_key = self::$group_prefix . $group_name;
        $group_data = self::getGroupAllUsers( $group_name );
        $group = $group_data ? $group_data : [];
        if(!$group) {
            return true;
        }
        $uuid_key = array_search($uuid,$group);
        if( $uuid_key ){
            unset($group[ $uuid_key ]);
        }
        var_dump( "--------{$group_name} leave end----------" );
        return CacheService::set($cache_key, @serialize($group), 86400 * 30);
    }

    /**
     * 统计组内有多少成员.获取游客的信息.
     * @param $group_name
     * @return int
     */
    public static function countUserInGroup($group_name)
    {
        $group_data = self::getGroupAllUsers( $group_name );
        $group = $group_data ? $group_data : [];
        return $group ? count($group) : 0;
    }

    /**
     * 通知组内所有成员.
     * @param $group_name
     * @param $msg
     * @return bool
     */
    public static function notifyGroupUserByGroupName($group_name, $msg)
    {
        $group_data = self::getGroupAllUsers( $group_name );
        $group = $group_data ? $group_data : [];
        if(!$group) {
            return false;
        }
        foreach($group as $guest_uuid) {
            $client_ids = Gateway::getClientIdByUid($guest_uuid);
            $client_ids && Gateway::sendToClient($client_ids[0], $msg);
        }
        return true;
    }

    /**
     * 获取组内所有成员
     * @param $group_name
     * @return array
     */
    public static function getGroupAllUsers($group_name)
    {
        $cache_key = self::$group_prefix . $group_name;
        $group_data = CacheService::get($cache_key);
        $group_data = @unserialize($group_data);
        return is_array($group_data ) ?$group_data:[];
    }

    /**
     * 移除组信息.
     * @param $group_name
     * @return bool
     */
    public static function removeGroup($group_name)
    {
        $cache_key = self::$group_prefix . $group_name;
        return CacheService::delete($cache_key);
    }

    /**
     * 检查用户是否在聊天组中.
     * @param $group_name
     * @param $uuid
     * @return bool
     */
    public static function checkUserInWaitGroup($group_name, $uuid)
    {
        $group_name = self::$wait_group_prefix . $group_name;

        return self::checkUserInGroup($group_name, $uuid);
    }

    /**
     * 加入等待组.
     * @param $group_name
     * @param $uuid
     * @return bool
     */
    public static function joinWaitGroup($group_name, $uuid)
    {
        $group_name = self::$wait_group_prefix . $group_name;

        return self::joinGroup($group_name, $uuid);
    }

    /**
     * 离开等待组
     * @param $group_name
     * @param $uuid
     * @return bool
     */
    public static function leaveWaitGroup($group_name, $uuid)
    {
        $group_name = self::$wait_group_prefix . $group_name;

        return self::leaveGroup($group_name, $uuid);
    }


    /**
     * 统计等待组内有多少成员.获取游客的信息.
     * @param $group_name
     * @return int
     */
    public static function countUserInWaitGroup($group_name)
    {
        $group_name = self::$wait_group_prefix . $group_name;

        return self::countUserInGroup($group_name);
    }

    /**
     * 移除等待组信息.
     * @param $group_name
     * @return bool
     */
    public static function removeWaitGroup($group_name)
    {
        $group_name = self::$wait_group_prefix . $group_name;
        return self::removeGroup($group_name);
    }

    /**
     * 通知等待组内所有成员.
     * @param $group_name
     * @param $msg
     * @return bool
     */
    public static function notifyWaitGroupUserByGroupName($group_name, $msg)
    {
        $group_name = self::$wait_group_prefix . $group_name;
        return self::notifyGroupUserByGroupName($group_name, $msg);
    }

    /**
     * 获取等待组内所有成员
     * @param $group_name
     * @return array
     */
    public static function getWaitGroupAllUsers($group_name)
    {
        $group_name = self::$wait_group_prefix . $group_name;
        return self::getGroupAllUsers($group_name);
    }
}
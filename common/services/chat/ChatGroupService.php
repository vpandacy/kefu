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

    /**
     * 游客加入组.
     * @param $group_name
     * @param $uuid
     * @return bool
     */
    public static function joinGroup($group_name, $uuid)
    {
        $cache_key = self::$group_prefix . $group_name;

        $group_data = CacheService::get($cache_key);

        $group = $group_data ? @json_decode($group_data, true) : [];

        array_push($group, $uuid);
        // 将组加入到缓存中.
        return CacheService::set($cache_key, json_encode($group), 86400 * 30);
    }

    /**
     * 离开组.
     * @param $group_name
     * @param $uuid
     * @return bool
     */
    public static function leaveGroup($group_name, $uuid)
    {
        $cache_key = self::$group_prefix . $group_name;

        $group_data = CacheService::get($cache_key);

        $group = @json_decode($group_data, true);

        if(!$group) {
            return true;
        }

        foreach($group as $key => $user_client_id) {
            if($user_client_id == $uuid) {
                unset($group[$key]);
            }
        }

        return CacheService::set($cache_key, json_encode($group), 86400 * 30);
    }

    /**
     * 统计组内有多少成员.获取游客的信息.
     * @param $group_name
     * @return int
     */
    public static function countUserInGroup($group_name)
    {
        $cache_key = self::$group_prefix . $group_name;

        $group_data = CacheService::get($cache_key);

        $group = @json_decode($group_data, true);

        return count($group);
    }

    /**
     * 通知组内所有成员.
     * @param $group_name
     * @param $msg
     * @return bool
     */
    public static function notifyGroupUserByGroupName($group_name, $msg)
    {
        $cache_key = self::$group_prefix . $group_name;

        $group_data = CacheService::get($cache_key);

        $group = @json_decode($group_data, true);

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
     * 移除组信息.
     * @param $group_name
     * @return bool
     */
    public static function removeGroup($group_name)
    {
        $cache_key = self::$group_prefix . $group_name;
        return CacheService::delete($cache_key);
    }
}
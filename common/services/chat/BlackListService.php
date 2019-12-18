<?php
namespace common\services\chat;

use common\services\BaseService;
use common\services\redis\CacheService;
use common\models\merchant\BlackList;
use common\services\ConstantService;

class BlackListService extends BaseService
{
    /**
     * 根据商户信息获取黑名单数据.
     * @param int $merchant_id
     * @return array
     */
    public static function getBlackListByMerchantId($merchant_id)
    {
        if(!$merchant_id) {
            return [];
        }

        $cache_key = 'merchant_blacklist_' . $merchant_id;
        $data = CacheService::get( $cache_key );

        if(!$data) {
            self::updateBlackListCache($merchant_id);
            $data = CacheService::get($cache_key);
        }

        return @json_decode($data, true);
    }

    /**
     * 添加黑名单.
     * @param string $client_ip
     * @param int $merchant_id
     * @param string $uuid
     * @param int $staff_id
     * @return bool
     */
    public static function addBlackList($client_ip, $merchant_id,$uuid, $staff_id)
    {
        $params = [
            'ip'            =>  $client_ip,
            'merchant_id'   =>  $merchant_id,
            'uuid'          =>  $uuid,
            'staff_id'      =>  $staff_id,
            'status'        =>  ConstantService::$default_status_true,
            'expired_time'  =>  DateHelper::getFormatDateTime('Y-m-d H:i:s', strtotime('+10 year')),
        ];

        // 这里要查询一次.得到游客的ID.
        $black = new BlackList();

        $black->setAttributes($params);

        if(!$black->save(0)) {
            return self::_err('数据保存失败，请联系管理员');
        }

        // 开始更新商户数据.
        self::updateBlackListCache($merchant_id);
        return true;
    }

    /**
     * 根据商户信息来更新缓存.
     * @param $merchant_id
     * @return bool
     */
    public static function updateBlackListCache($merchant_id)
    {
        // 开始更新商户数据.
        $cache_key = 'merchant_blacklist_' . $merchant_id;

        $black_list = BlackList::find()
            ->where(['merchant_id'=>$merchant_id,'status'=>ConstantService::$default_status_true])
            ->select(['ip'])
            ->column();

        $data = json_encode($black_list);
        CacheService::set($cache_key, $data, 86400 * 30);
        return true;
    }
}
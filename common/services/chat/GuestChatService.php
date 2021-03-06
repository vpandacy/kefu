<?php

namespace common\services\chat;

use common\components\helper\DateHelper;
use common\models\merchant\GuestHistoryLog;
use common\models\merchant\GuestChatLog;
use common\models\merchant\Member;
use common\models\uc\Staff;
use common\services\BaseService;
use common\services\CommonConstant;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\GlobalUrlService;

class GuestChatService extends BaseService
{
    /**
     * 完善信息.
     * @param array $params
     * @return bool
     */
    public static function addGuest($params = [])
    {
        $params = array_merge($params, GuestService::getProvinceByClientIP($params['client_ip']));

        $params['source'] = CommonService::getSourceByUa($params['client_ua']);

        if ($params['referer_url']) {
            // 来源媒体.
            $params['referer_media'] = GuestService::getRefererSidByUrl($params['referer_url']);
            // 保存关键词.
            $params['keyword'] = GuestService::getKeywordByReferer($params['referer_url']);
        }

        $member = Member::findOne(['uuid' => $params['uuid']]);

        if ($member) {
            $params['member_id'] = $member['id'];
        }

        $model = new GuestHistoryLog();
        $model->setAttributes($params);
        return $model->save(0);
    }

    /*
     * 需要给每一次对话分配一个唯一id，然后更新日志就更方便了，
     * 不然会出现一种情况，就是一个用户一直刷新，每次更新计算关闭日志的时候是找的最后一条，这个不精确
     * 频繁刷新就会出现更新最后一条的情况（也许应该更新前面几条）
     * 关闭游客时的保存. 这里直接用client_id 来解决这个问题
     */
    public static function closeGuest($params = [])
    {
        $query = GuestHistoryLog::find();
        if (isset($params['client_id'])) {
            $query->andWhere(["client_id" => $params['client_id']]);
        }

        $query->andWhere([
            "status" => ConstantService::$default_status_neg_1,
            "merchant_id" => $params['merchant_id'],
            "uuid" => $params['uuid'],
            "closed_time" => ConstantService::$default_datetime
        ]);

        //缩小时间范围
        $query = $query->andWhere([ ">=","created_time",DateHelper::getFormatDateTime( "Y-m-d 00:00:00",strtotime("-1 days") ) ]);

        $guest_log = $query->orderBy(["id" => SORT_DESC])->limit(1)->one();
        if( !$guest_log ){
            return false;
        }
        $member = Member::findOne(['uuid' => $params['uuid'], 'merchant_id' => $params['merchant_id']]);
        $guest_log->closed_time = $params['closed_time'];
        $guest_log->member_id = $member ? $member['id'] : 0;
        if( !$guest_log['cs_id'] ){
            $guest_log->cs_id = $params['cs_id'];
        }
        $guest_log->chat_duration = strtotime($guest_log->closed_time) - strtotime($guest_log->created_time);
        $guest_log->status = $params['status'];
        return $guest_log->save(0);

//            if ($member) {
//                // 这里要批量去更新这次的会话.不更新了会出现性能问题
//                GuestChatLog::updateAll(['member_id' => $member['id']], ['guest_log_id' => $guest_log['id']]);
//            }
    }

    /*
     * 需要给每一次对话分配一个唯一id，然后更新日志就更方便了，
     * 不然会出现一种情况，就是一个用户一直刷新，每次更新计算关闭日志的时候是找的最后一条，这个不精确
     * 频繁刷新就会出现更新最后一条的情况（也许应该更新前面几条）
     * 更新客服信息
     */
    public static function updateGuest($params = [])
    {


        $query = GuestHistoryLog::find()
            ->where(["status" => ConstantService::$default_status_neg_1])
            ->andWhere(["merchant_id" => $params['merchant_id'], "uuid" => $params['uuid']]);

        $query = $query->andWhere([ ">=","created_time",DateHelper::getFormatDateTime( "Y-m-d 00:00:00",strtotime("-1 days") ) ]);
        $guest_log = $query->orderBy(["id" => SORT_DESC])->limit(1)->one();

        if (!$guest_log) {
            return false;
        }

        $guest_log->setAttributes([
            'cs_id' => $params['cs_id'], // 更换为新对的客服.
        ]);

        return $guest_log->save( 0 );
    }

    /**
     * 添加日志记录.
     * @param array $params
     * @return bool
     */
    public static function addChatLog($params = [])
    {
        $staff = Staff::findOne(['sn' => $params['cs_sn']]);

        $guest_log = GuestHistoryLog::find()
            ->where(["status" => ConstantService::$default_status_neg_1])
            ->andWhere(["merchant_id" => $staff['merchant_id'], "uuid" => $params['uuid']])
            ->orderBy(["id" => SORT_DESC])
            ->limit(1)
            ->one();

        unset($params['cs_sn']);
        $params['merchant_id'] = $staff['merchant_id'];
        $params['cs_name'] = $staff['nickname'];
        $params['cs_id'] = $staff['id'];
        $params['guest_log_id'] = $guest_log['id'];
        $params['member_id'] = $guest_log['member_id'];

        $model = new GuestChatLog();
        $model->setAttributes($params);
        if( !$model->save(0) ){
            return false;
        }
        //对GuestHistoryLog 有几个字段进行更新 ,has_talked ,has_mobile,has_email
        $force_update = false;
        if( !$guest_log['has_talked'] && $params['from_id'] == $params['uuid']){
            $guest_log->setAttribute("has_talked",ConstantService::$default_status_true);
            $force_update = true;
        }

        if( !$guest_log['has_mobile']  && preg_match("/\d{11}/",$params['content']) ){
            $guest_log->setAttribute("has_mobile",ConstantService::$default_status_true);
            $force_update = true;
        }

        if( !$guest_log['has_email']  && preg_match("/([a-z0-9\-_\.]+@[a-z0-9]+\.[a-z0-9\-_\.]+)+/i",$params['content']) ){
            $guest_log->setAttribute("has_email",ConstantService::$default_status_true);
            $force_update = true;
        }

        if( $force_update ){
            $guest_log->save( 0 );
        }

        return true;
    }

    /**
     * 获取最后一次的聊天信息.
     * @param $uuid
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getLastGuestChatLog($uuid)
    {
        $guest_log = GuestHistoryLog::find()
            ->where([
                'uuid' => $uuid
            ])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        return $guest_log;
    }

    /**
     * 获取所有的在线和离线的用户信息.
     * @param array $online_users 在线聊的游客
     * @param array $wait_users 在等待中的游客
     * @return array
     */
    public static function getAllUsersInfo($online_users, $wait_users)
    {
        $all_users = [];

        if ($online_users) {
            foreach ($online_users as $uuid) {
                $tmp_ret = self::genGuestInfo($uuid);
                if( !$tmp_ret ){
                    continue;
                }
                $all_users[] = $tmp_ret;
            }
        }

        if ($wait_users) {
            foreach ($wait_users as $uuid) {
                $tmp_ret = self::genGuestInfo($uuid);
                if( !$tmp_ret ){
                    continue;
                }
                $all_users[] = $tmp_ret;
            }
        }

        return $all_users;
    }

    /**
     * 根据uuid生成一个临时游客.
     * @param $uuid
     * @return array
     */
    public static function genGuestInfo($uuid)
    {
        $cache_params = ChatEventService::getGuestBindCache($uuid);
        if( !$cache_params ){
            return [];
        }
        return [
            // 随机生成一个昵称.
            'nickname' => substr($uuid, strlen($uuid) - 12),
            'uuid' => $uuid,
            'avatar' => GlobalUrlService::buildPicStaticUrl('hsh', ConstantService::$default_avatar),
            'allocation_time' => date('H:i:s'),
            'is_online' => ConstantService::$default_status_true,
            'source' => isset($cache_params['source']) ? $cache_params['source'] : 2,
            'media' => isset($cache_params['media']) ? $cache_params['media'] : 0,
        ];
    }
}
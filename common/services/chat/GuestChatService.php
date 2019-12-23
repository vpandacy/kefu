<?php
namespace common\services\chat;

use common\models\kefu\chat\GuestHistoryLog;
use common\models\merchant\GuestChatLog;
use common\models\uc\Staff;
use common\services\BaseService;
use common\services\ConstantService;
use common\services\GlobalUrlService;

class GuestChatService extends BaseService
{
    public static function addGuest($params = [])
    {
        //这个地方需要完善判断
        $model = new GuestHistoryLog();
        $model->setAttributes($params);
        return $model->save(0);
    }

    public static function closeGuest( $params = [] ){
        $guest_log = GuestHistoryLog::find()
            ->where([ "client_id" => $params['client_id'],"status" => ConstantService::$default_status_neg_1 ])
            ->andWhere([ "merchant_id" => $params['merchant_id'],"uuid" => $params['uuid'] ])
            ->andWhere([ "closed_time" => ConstantService::$default_datetime ])
            ->orderBy([ "id" => SORT_DESC ])->limit(1)->one();
        if( $guest_log ){
            $guest_log->closed_time = $params['closed_time'];
            $guest_log->cs_id = $params['cs_id'];
            $guest_log->chat_duration = strtotime( $guest_log->closed_time ) - strtotime( $guest_log->created_time );
            $guest_log->status = $params['status'];
            $guest_log->save(0);
        }
        return true;
    }

    /**
     * 更新信息.
     * @param array $params
     * @return bool
     */
    public static function updateGuest($params = [])
    {
        $guest_log = GuestHistoryLog::find()
            ->where(["status" => ConstantService::$default_status_neg_1])
            ->andWhere([ "merchant_id" => $params['merchant_id'],"uuid" => $params['uuid'] ])
            ->orderBy([ "id" => SORT_DESC ])
            ->limit(1)
            ->one();

        if(!$guest_log) {
            return false;
        }

        $guest_log->setAttributes([
            'cs_id' =>  $params['cs_id'], // 更换为新对的客服.
        ]);

        return $guest_log->save();
    }

    /**
     * 添加日志记录.
     * @param array $params
     * @return bool
     */
    public static function addChatLog($params = [])
    {
        $staff = Staff::findOne(['sn'=>$params['cs_sn']]);

        $guest_log = GuestHistoryLog::find()
            ->where(["status" => ConstantService::$default_status_neg_1])
            ->andWhere([ "merchant_id" => $staff['merchant_id'],"uuid" => $params['uuid'] ])
            ->orderBy([ "id" => SORT_DESC ])
            ->limit(1)
            ->one();

        unset($params['cs_sn']);
        $params['merchant_id']  = $staff['merchant_id'];
        $params['cs_name']      = $staff['nickname'];
        $params['cs_id']        = $staff['id'];
        $params['guest_log_id'] = $guest_log['id'];
        $params['member_id']    = $guest_log['member_id'];

        $model = new GuestChatLog();
        $model->setAttributes($params);
        return $model->save(0);
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
                'uuid'  =>  $uuid
            ])
            ->orderBy(['id'=>SORT_DESC])
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

        if($online_users) {
            foreach($online_users as $uuid) {
                $all_users[] = self::genGuestInfo($uuid);
            }
        }

        if($wait_users) {
            foreach($wait_users as $uuid) {
                $all_users[] = self::genGuestInfo($uuid);
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
        return [
            // 随机生成一个昵称.
            'nickname'  =>  'Guest-' . substr($uuid, strlen($uuid) - 12),
            'uuid'      =>  $uuid,
            'avatar'    =>  GlobalUrlService::buildPicStaticUrl('hsh',ConstantService::$default_avatar),
            'allocation_time'   =>  date('H:i:s'),
            'is_online' =>  ConstantService::$default_status_true
        ];
    }
}
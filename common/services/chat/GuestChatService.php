<?php

namespace common\services\chat;


use common\models\kefu\chat\GuestHistoryLog;
use common\services\BaseService;
use common\services\ConstantService;

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
            $guest_log->chat_duration = strtotime( $guest_log->closed_time ) - strtotime( $guest_log->created_time );
            $guest_log->status = $params['status'];
            $guest_log->save(0);
        }
        return true;
    }
}
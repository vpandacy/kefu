<?php
namespace common\services\chat;

use common\models\merchant\GroupChat;
use common\models\merchant\GroupChatStaff;
use common\models\uc\Staff;
use common\services\applog\AppLogService;
use common\services\BaseService;
use common\services\ConstantService;
use common\services\redis\CacheService;
use common\services\uc\MerchantService;
use yii\db\Expression;

class ChatEventService extends BaseService
{
    /**
     * 智能分配客服
     * @param string $sn
     * @param string $code
     * @return array|false
     */
    public static function getKFByRoute( $sn = null , $code = ''){
        if( !$sn ){
            return self::_err("商户信息异常-1~~");
        }

        $merchant_info = MerchantService::getInfoBySn( $sn );
        if( !$merchant_info ){
            return self::_err("商户信息异常-2~~");
        }

        $query = Staff::find()->where([
            'status' => ConstantService::$default_status_true,
            'merchant_id' => $merchant_info['id'],
            'is_online' =>  1,
        ]);

        // 先简单查询风格信息.
        if($code) {
            // 查询风格.
            $chat = GroupChat::find()
                ->where([
                    'sn'    =>  $code,
                    'merchant_id'   =>  $merchant_info['id'],
                    'status'    =>  ConstantService::$default_status_true
                ])
                ->one();
            if(!$chat) {
                return self::_err('风格信息异常-3~~');
            }

            $staff_ids = GroupChatStaff::find()
                ->where([
                    'group_chat_id' =>  $chat['id'],
                    'status'        =>  ConstantService::$default_status_true
                ])
                ->select(['staff_id'])
                ->column();

            $query->andWhere(['id'=>$staff_ids]);
        }

        $staff_info = $query->limit(1)
            ->asArray()
            ->one();

        if( !$staff_info ){
            return self::_err("暂无客服~~");
        }

        return $staff_info;
    }

    public static function buildMsg( $cmd ,$data ){
        $params = [
            "cmd" => $cmd,
            "data" => $data
        ];
        return json_encode( $params );
    }

    public static function setGuestBindCache( $client_id , $params = [] ){
        $cache_key = "guest_{$client_id}";
        $data = json_encode( $params );
        return CacheService::set($cache_key,$data,86400 * 30 );
    }

    public static function getGuestBindCache(  $client_id ){
        $cache_key = "guest_{$client_id}";
        $data = CacheService::get( $cache_key );
        return @json_decode( $data,true );
    }

    public static function clearGuestBindCache( $client_id ){
        $cache_key = "guest_{$client_id}";
        return CacheService::delete( $cache_key );
    }


    public static function handlerError( $error_msg = "" ){
        self::consoleLog( $error_msg );
        AppLogService::addErrLog( "app-ws", $error_msg);
    }
}
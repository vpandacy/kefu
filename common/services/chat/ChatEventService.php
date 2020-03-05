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
     * 智能分配客服.
     * @param string $uuid
     * @param string $sn
     * @param string $code
     * @param string $client_ip
     * @return array|false
     */
    public static function getKFByRoute( $uuid ,$sn = null , $code = '', $client_ip = ''){
        if( !$sn ){
            return self::_err("商户信息异常-1~~");
        }

        $merchant_info = MerchantService::getInfoBySn( $sn );
        if( !$merchant_info ){
            return self::_err("商户信息异常-2~~");
        }

        // 这里要先根据黑名单来处理一下.
        $black_list = BlackListService::getBlackListByMerchantId($merchant_info['id']);

        if(in_array($client_ip, $black_list)) {
            return self::_err('黑名单游客，请联系管理进行解绑操作');
        }

        $query = Staff::find()->where([
            'status' => ConstantService::$default_status_true,
            'merchant_id' => $merchant_info['id'],
            'is_online' =>  ConstantService::$default_status_true,
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

        $staffs = $query->asArray()->all();

        if( !$staffs ){
            return self::_err("当前客服不在线，请于下方开始留言~~");
        }

        return self::assignCustomerService($uuid, $staffs);
    }

    /**
     * 分配客服.按照最少接待处理.
     * @param $uuid
     * @param $staffs
     * @return array
     */
    private static function assignCustomerService($uuid, $staffs)
    {
        $source = [];
        foreach($staffs as $key=>$staff) {
            $source[$staff['sn']] = $staff;
        }

        $mapping = [];
        foreach($staffs as $key=> $staff) {
            $online_users = ChatGroupService::getGroupAllUsers($staff['sn']);
            // 在客服的在线区域.就直接返回这个客服.
            if(in_array($uuid,$online_users)) {
                $staff['act'] = 'success';
                return $staff;
            }

            // 如果在客服的等待区域,还是直接返回该客服.
            $wait_users = ChatGroupService::getWaitGroupAllUsers( $staff['sn'] );
            if(in_array($uuid, $wait_users)) {
                $staff['act'] = 'wait';
                return $staff;
            }

            $num = count($online_users);
            // 过滤掉已经满的客服
            if($staff['listen_nums'] <= $num) {
                unset($staffs[$key]);
                continue;
            }

            $mapping[$staff['sn']] = $num;
        }

        // 随机返回一个. 如果所有人都满了. 那就随机分配了. 不算等待区.
        if(!$staffs) {
            $staff = $source[array_rand($source)];
            $staff['act'] = 'wait';
            return $staff;
        }
        $nums = array_values($mapping);
        sort($nums);

        $min = array_search($nums[0], $mapping);

        $staff = $source[$min];
        // 标记分配成功.可以直接开始聊天.
        $staff['act'] = 'success';
        return $staff;
    }


    public static function buildMsg( $cmd ,$data = [] ){
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

    /**
     * 兼容.
     * @param $client_id
     * @return array
     */
    public static function getGuestBindCache(  $client_id ){
        $cache_key = "guest_{$client_id}";
        $data = CacheService::get( $cache_key );
        $data = @json_decode( $data,true );
        return $data ? $data : [];
    }

    public static function clearGuestBindCache( $client_id ){
        $cache_key = "guest_{$client_id}";
        return CacheService::delete( $cache_key );
    }

    /**
     * 设置客服和client_id的绑定关系
     * @param $client_id
     * @return mixed
     */
    public static function setCSBindCache($client_id, $params = []) {
        $cache_key = 'cs_' . $client_id;
        $data = json_encode($params);
        return CacheService::set($cache_key, $data, 86400 * 30);
    }

    /**
     * 获取客服和client_id的绑定关系
     * @param $client_id
     * @return mixed
     */
    public static function getCSBindCache($client_id) {
        $cache_key = 'cs_' . $client_id;
        $data = CacheService::get($cache_key);
        return @json_decode($data, true);
    }

    /**
     * 清除客服和client_id的绑定关系
     * @param $client_id
     * @return mixed
     */
    public static function clearCSBindCache($client_id)
    {
        $cache_key = 'cs_' . $client_id;
        return CacheService::delete($cache_key);
    }


    public static function handlerError( $error_msg = "" ){
        self::consoleLog( $error_msg );
        AppLogService::addErrLog( "app-ws", $error_msg);
    }
}
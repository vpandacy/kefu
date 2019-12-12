<?php
namespace console\services;

use common\models\merchant\GroupChat;
use common\models\merchant\GroupChatStaff;
use common\models\uc\Merchant;
use common\models\uc\Staff;
use common\services\BaseService;
use common\services\ConstantService;
use GatewayWorker\Lib\Gateway;

class EventsDispatchService extends BaseService
{
    /**
     * 先调用通
     * @param $client_id
     * @param $message
     */
    public static function chatMessage($client_id, $message)
    {
        $data = $message['data'];
        Gateway::sendToUid($message['to'], self::buildMsg('chat',[
            'msg'   =>  $data['msg'],
            'form'  =>  $message['to']  // 来自谁的消息.
        ]));
    }


    /**
     * 这里是游客加入聊天.
     * @param string $client_id
     * @param array $message
     * @return bool
     */
    public static function guestIn($client_id, $message)
    {
        $uuid = isset($message['form']) ? $message['form'] : '';

        $data = $message['data'];

        if(!$uuid) {
            return self::_err('暂不支持游客登录呢');
        }

        // 获取
        if(!array_key_exists('ua', $data) || !$data['ua']) {
            return self::_err('无法获取游客浏览器信息');
        }

        if(!array_key_exists('referer', $data)) {
            return self::_err('无法获取游客浏览器的来源地址');
        }

        if(!array_key_exists('url', $data)) {
            return self::_err('无法获取游客的访问地址');
        }

        if(!array_key_exists('msn',$data)) {
            return self::_err('无法获取有效的商户信息');
        }

        $merchant = Merchant::findOne(['sn'=>$data['msn'],'status'=>ConstantService::$default_status_true]);

        if(!$merchant) {
            return self::_err('商户信息未注册,请联系客服系统进行注册');
        }

        // 以上都成功过后,就开始分配客服了. 绑定信息.
        Gateway::bindUid($client_id, $uuid);
        $cs= self::getCustomerService($data['code'], $merchant);

        if(!$cs) {
            return Gateway::sendToUid($uuid,self::buildMsg('assign_kf', ['msg'=>self::getLastErrorMsg()] ));
        }

        // 同时还得给客服来一次消息. 不然客服都不知道有游客进来了.
        Gateway::sendToUid($cs,self::buildMsg(' assign_kf', [
            'msg'   =>  '分配成功',
            'customer'  =>  $uuid
        ]));

        return Gateway::sendToUid($uuid,self::buildMsg('assign_kf',[
            'msg'   =>  '分配成功',
            'cs_sn' =>  $cs
        ]));
    }

    /**
     * @todo 客服绑定id.方便后续调用.
     * @param $client_id
     * @param $message
     */
    public static function guestInCs($client_id, $message) {
        $sn = $message['data']['cs_sn'];

        Gateway::bindUid($client_id, $sn);
    }


    /**
     * 这里获取商户下在线客服的sn.
     * @param string $code 风格
     * @param $merchant
     * @return bool|false
     */
    private static function getCustomerService($code, $merchant)
    {
        // 这里是普通的风格.默认就是所有的员工.
        $staff = self::getStaffIdsByCode($code, $merchant['id']);

        if(!$staff) {
            return self::_err('当前商户下没有客服,请留言');
        }

        // 先写死.后面在修改成根据客服的在线状态.获取客服.还有一些分配的规则.
        return 'qkztwtm1';
    }

    /**
     * 获取员工.还没有开始分配.
     * @param $code
     * @param $merchant_id
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    private static function getStaffIdsByCode($code,$merchant_id)
    {
        if(!$code) {
            $staff = Staff::find()
                ->where([
                    'merchant_id'   =>  $merchant_id,
                    'status'        =>  ConstantService::$default_status_true,
                ])
                ->select([
                    'id','sn'
                ])
                ->asArray()
                ->all();

            return $staff;
        }

        $group_id = GroupChat::find()
            ->where([
                'sn'    =>  $code,
                'merchant_id'   =>  $merchant_id,
                'status'=>  ConstantService::$default_status_true
            ])
            ->select(['id'])
            ->scalar();

        if(!$group_id) {
            return false;
        }

        $staff_ids = GroupChatStaff::find()->where(['group_chat_id'=>$group_id])
            ->select(['staff_id'])
            ->column();

        if(!$staff_ids) {
            return false;
        }

        $staff = Staff::find()
            ->where(['id'=>$staff_ids])
            ->andWhere(['merchant_id'=>$merchant_id, 'status'=>ConstantService::$default_status_true])
            ->select([
                'id','sn'
            ])
            ->asArray()
            ->all();

        return $staff;
    }

    /**
     * 生成绑定的信息.后面看看放在那里合适.
     * @param string $cmd
     * @param array $data
     * @param int $code
     * @return string
     */
    public static function buildMsg($cmd, $data = [], $code = 200)
    {
        return json_encode([
            'cmd'   =>  $cmd,
            'data'  =>  $data,
            'code'  =>  $code
        ]);
    }
}
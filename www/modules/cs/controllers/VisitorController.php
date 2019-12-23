<?php
namespace www\modules\cs\controllers;

use common\components\helper\DateHelper;
use common\components\helper\ModelHelper;
use common\components\helper\ValidateHelper;
use common\components\ip\IPDBQuery;
use common\models\kefu\chat\GuestHistoryLog;
use common\models\merchant\GroupChat;
use common\models\merchant\GuestChatLog;
use common\models\merchant\Member;
use common\models\uc\Staff;
use common\services\chat\BlackListService;
use common\services\chat\GuestChatService;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\QueueListService;
use www\modules\cs\controllers\common\BaseController;

/**
 * Class VisitorController
 * @package www\modules\cs\controllers
 */
class VisitorController extends BaseController
{
    /**
     * 关闭聊天. 强制关闭.
     */
    public function actionClose()
    {
        $uuid = $this->post('uuid','');

        if(!$uuid) {
            return $this->renderErrJSON('请选择正确的游客ID');
        }

        // 如果是正确的.这里就要关闭游客和游客对应的链接.
        $ret = QueueListService::push2Guest(QueueConstant::$queue_guest_chat,[
            'cmd'   =>  ConstantService::$chat_cmd_close_guest,
            'data'  =>  [
                'f_id'  =>  $this->current_user['sn'],
                't_id'  =>  $uuid,
                'kf_id' =>  $this->current_user['id'],
                'msn'   =>  $this->merchant_info['sn'],
                'kf_sn' =>  $this->current_user['sn'],
            ],
        ]);

        if(!$ret) {
            return $this->renderErrJSON('非法操作，暂时无法找到该游客.');
        }

        return $this->renderJSON('操作成功');
    }

    /**
     * 将游客加入黑名单.
     */
    public function actionBlacklist()
    {
        // 这里需要将游客加入到黑名单当中去.
        $uuid = $this->post('uuid','');

        if(!$uuid) {
            return $this->renderErrJSON('非法的游客信息');
        }

        $guest = GuestHistoryLog::find()
            ->where(['uuid'=>$uuid,'merchant_id'=>$this->getMerchantId()])
            ->orderBy([ "id" => SORT_DESC ])->limit(1)
            ->one();

        if(!$guest) {
            return $this->renderErrJSON('暂未找到该游客');
        }

        if(!BlackListService::addBlackList($guest['client_ip'],$this->getMerchantId(), $uuid, $this->getStaffId())) {
            return $this->renderErrJSON(BlackListService::getLastErrorMsg());
        }

        QueueListService::push2Guest(QueueConstant::$queue_guest_chat, [
            'cmd'   =>  ConstantService::$chat_cmd_close_guest,
            'data'  =>  [
                'f_id'  =>  $this->current_user['sn'],
                't_id'  =>  $uuid
            ],
        ]);

        // 主动关闭.
        QueueListService::push2ChatDB(QueueConstant::$queue_chat_log, [
            'cmd'   =>  ConstantService::$chat_cmd_close_guest,
            'data'  =>  [
                'f_id'  =>  $this->current_user['sn'],
                't_id'  =>  $uuid,
                'msn'   =>  $this->merchant_info['sn'],
                'cs_id' =>  $this->getStaffId(),
                'close_time'    =>  DateHelper::getFormatDateTime(),
            ],
        ]);

        // 主动关闭事件.
        if(!$guest->save()) {
            return $this->renderErrJSON('数据保存失败，请联系管理员');
        }

        return $this->renderJSON('操作成功');
    }

    /**
     * 保存游客数据.
     */
    public function actionSave()
    {
        $name = $this->post('name',''); // 姓名.
        $mobile = $this->post('mobile',''); // 手机号.
        $email = $this->post('email',''); // 邮件.
        $qq = $this->post('qq',''); // QQ号码.
        $wechat = $this->post('wechat',''); // 微信号.
        $uuid = $this->post('uuid',''); // uuid.
        $desc = $this->post('desc',''); // 描述.
        $code = $this->post('code',0);

        if($name && !ValidateHelper::validLength($name,1,255)) {
            return $this->renderErrJSON('请输入正确长度的名称');
        }

        if($qq && !ValidateHelper::validLength($qq,1,13)) {
            return $this->renderErrJSON('请输入正确长度的ＱＱ号');
        }

        if($mobile && !ValidateHelper::validMobile($mobile)) {
            return $this->renderErrJSON('请输入正确的手机号');
        }

        if($email && !ValidateHelper::validEmail($email)) {
            return $this->renderErrJSON('请输入正确格式的邮箱');
        }

        if($wechat && !ValidateHelper::validLength($wechat,1,255)) {
            return $this->renderErrJSON('请填写正确的微信号长度');
        }

        if($code) {
            $group_chat = GroupChat::findOne(['sn'=>$code,'merchant_id'=>$this->getMerchantId()]);
            if(!$group_chat) {
                return $this->renderErrJSON('请选择正确的风格');
            }
        }

        if(!$uuid) {
            return $this->renderErrJSON('非法请求');
        }
        // 开始保存信息.
        $member = Member::findOne(['uuid'=>$uuid]);
        // 如果是第一次就要存储ip信息.
        if(!$member) {
            $member = new Member();
            $guest_log = GuestChatService::getLastGuestChatLog($uuid);

            $member->setAttributes([
                'reg_ip'    =>  $guest_log['client_ip'],
                'province_id'   =>  $guest_log['province_id'],
                'city_id'   =>  $guest_log['city_id'],
                'source'    =>  $guest_log['source'],
            ]);
        }

        $params = [
            'merchant_id'   => $this->getMerchantId(),
            'cs_id'         => $this->getStaffId(),
            'chat_style_id' => $code ? $group_chat['id'] : 0,   // 风格分组id.
            'name'  => $name,
            'mobile'=> $mobile,
            'email' => $email,
            'qq'    => $qq,
            'wechat'=> $wechat,
            'uuid'  => $uuid,
            'desc'  => $desc
        ];

        foreach($params as $key=>$value) {
            if($value === '') {
                unset($params[$key]);
            }
        }

        $member->setAttributes($params);

        if(!$member->save(0)) {
            return $this->renderErrJSON('数据保存失败，请联系管理员');
        }

        return $this->renderJSON('保存成功');
    }

    /**
     * 获取游客数据.
     */
    public function actionInfo()
    {
        $uuid = $this->post('uuid','');

        if(!$uuid) {
            return $this->renderErrJSON('非法请求');
        }
        // 开始保存信息.
        $member = Member::find()
            ->asArray()
            ->where(['uuid'=>$uuid])
            ->one();

        // 获取IP地址.获取来源.获取咨询界面.
        $history = GuestHistoryLog::find()
            ->where([
//                "status" => ConstantService::$default_status_neg_1,
                "merchant_id" => $this->getMerchantId(),
                "uuid" => $uuid,
            ])
            ->orderBy([ "id" => SORT_DESC ])
            ->asArray()
            ->limit(1)
            ->one();

        $history['source'] = isset(ConstantService::$guest_source[$history['source']])
            ? ConstantService::$guest_source[$history['source']]
            : '暂无';

        $history['province'] = IPDBQuery::find($history['client_ip']);
        return $this->renderJSON([
            'member'    =>  $member,
            'history'   =>  $history
        ],'保存成功');
    }

    /**
     * 游客转让.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionTransfer()
    {
        $cs_id = $this->post('cs_id',0);

        if(!$cs_id) {
            return $this->renderErrJSON('请选择正确的客服');
        }

        if($cs_id == $this->getStaffId()) {
            return $this->renderErrJSON('游客无法转给自己');
        }

        $uuid = $this->post('uuid','');

        $cs = Staff::find()
            ->where([
                'merchant_id'   =>  $this->getMerchantId(),
                'status'        =>  ConstantService::$default_status_true,
                'is_online'     =>  ConstantService::$default_status_true,
                'id'            =>  $cs_id
            ])
            ->asArray()
            ->select(['id','sn','name','avatar'])
            ->one();

        if(!$cs) {
            return $this->renderErrJSON('该客服已经下线了');
        }

        $cs['avatar'] = GlobalUrlService::buildPicStaticUrl('hsh',$cs['avatar']);

        if(!GuestChatService::updateGuest([
            'uuid'  =>  $uuid,
            'merchant_id'   =>  $this->getMerchantId(),
            'cs_id' =>  $cs['id']
        ])) {
            return $this->renderErrJSON('请输入正确的游客信息');
        }

        // 开始处理信息.
        QueueListService::push2Guest(QueueConstant::$queue_guest_chat, [
            'cmd'   =>  ConstantService::$chat_cmd_change_kf,
            'data'  =>  [
                'f_id'  =>  $this->current_user['sn'],
                't_id'  =>  $uuid,
                'cs'    =>  $cs,
            ]
        ]);

        // 通知登录.
        QueueListService::push2CS(QueueConstant::$queue_cs_chat, [
            'cmd'   =>  ConstantService::$chat_cmd_guest_connect,
            'data'  =>  [
                'f_id'  =>  $uuid,
                't_id'  =>  $cs['sn'],
                'nickname'  =>  'Guest-' . substr($uuid, strlen($uuid) - 12),
                'avatar'    =>  GlobalUrlService::buildPicStaticUrl('hsh',ConstantService::$default_avatar),
            ],
        ]);

        return $this->renderJSON([],'分配成功');
    }

    /**
     * 获取游客的轨迹.
     */
    public function actionHistory()
    {
        $uuid = $this->post('uuid','');

        if(!$uuid) {
            return $this->renderErrJSON('请选择正确的游客信息');
        }

        // 开始查询. 这个查询有点伤.
        $history = GuestHistoryLog::find()
            ->where([
                'merchant_id'   =>  $this->getMerchantId(),
                'uuid'          =>  $uuid,
                'status'        =>  ConstantService::$default_status_true,
            ])
            ->andWhere(['>','created_time',DateHelper::getFormatDateTime('Y-m-d 00:00:00', strtotime('-3 day'))])
            ->asArray()
            // 倒序排.
            ->orderBy(['id'=>SORT_DESC])
            ->select(['id','cs_id','referer_url','referer_media','land_url','client_ip','source','chat_duration','created_time'])
            ->all();

        if($history) {
            $staffs = ModelHelper::getDicByRelateID($history,Staff::className(), 'cs_id','id',['name']);

            foreach($history as $key => $h) {
                $h['staff_name'] = isset($staffs[$h['cs_id']])
                    ? $staffs[$h['cs_id']]['name']
                    : '暂无接待员工';

                $history[$key] = $h;
            }
        }

        return $this->renderJSON($history,'获取成功');
    }

    /**
     * 获取游客跟客服的聊天记录.
     */
    public function actionMessage()
    {
        // 最后一次聊天的时间.
        $last_time = $this->post('last_time',date('Y-m-d H:i:s'));

        $uuid = $this->post('uuid', '');

        if(!$uuid) {
            return $this->renderJSON([],'暂无信息');
        }

        $guest_chat_log = GuestChatLog::find()
            ->where(['<','created_time', $last_time])
            ->andWhere(['merchant_id'=>$this->getMerchantId(),'uuid'=> $uuid])
            ->orderBy(['id'=>SORT_DESC])
            ->limit($this->page_size)
            ->asArray()
            ->select(['id','cs_id','uuid','from_id','content','created_time'])
            ->all();

        if($guest_chat_log) {
            $customers = ModelHelper::getDicByRelateID($guest_chat_log, Staff::className(), 'cs_id','id',['nickname','avatar']);

            foreach($guest_chat_log as $key=>$log) {
                $log['staff_name'] = isset($customers[$log['cs_id']])
                    ? $customers[$log['cs_id']]['nickname']
                    : '暂无';

                $log['cs_avatar'] = isset($customers[$log['cs_id']])
                    ? GlobalUrlService::buildPicStaticUrl('hsh',$customers[$log['cs_id']]['avatar'])
                    : GlobalUrlService::buildPicStaticUrl('hsh',ConstantService::$default_avatar);

                $guest_chat_log[$key] = $log;
            }
        }

        return $this->renderJSON($guest_chat_log,'获取成功');
    }
}
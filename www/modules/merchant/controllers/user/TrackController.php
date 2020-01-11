<?php
namespace www\modules\merchant\controllers\user;

use common\components\DataHelper;
use common\components\helper\ModelHelper;
use common\models\merchant\City;
use common\models\merchant\GuestChatLog;
use common\models\merchant\GuestHistoryLog;
use common\models\merchant\GroupChat;
use common\models\merchant\Member;
use common\models\uc\Staff;
use www\modules\merchant\controllers\common\BaseController;
use yii\db\Expression;

class TrackController extends BaseController
{
    public function actionIndex()
    {
        if($this->isGet()) {
            $group_id = $this->get('group_id',0);
            $time = $this->get('time','');
            $mobile = $this->get('mobile','');
            $url = $this->get('url','');
            $staff_id = intval($this->get('staff_id',0));
            $qq = $this->get('qq','');
            $wechat = $this->get('wechat','');
            $email = $this->get('email','');


            $groups = GroupChat::findAll(['merchant_id'=>$this->getMerchantId()]);
            $staffs  = Staff::find()
                ->where(['merchant_id'=>$this->getMerchantId()])
                ->asArray()
                ->all();

            return $this->render('index',[
                'groups'    =>  $groups,
                'staffs'    =>  $staffs,
                'search_conditions' =>  [
                    'group_id'  =>  $group_id,
                    'time'      =>  $time,
                    'mobile'    =>  $mobile,
                    'url'       =>  $url,
                    'staff_id'  =>  $staff_id,
                    'email'     =>  $email,
                    'wechat'    =>  $wechat,
                    'qq'        =>  $qq,
                ],
            ]);
        }

        $group_id = intval($this->post('group_id',0));
        $time = $this->post('time','');
        $staff_id = intval($this->post('staff_id',0));
        $mobile = trim($this->post('mobile',''));
        $url = trim($this->post('url',''));
        $qq = $this->post('qq',0);
        $wechat = $this->post('wechat',0);
        $email = $this->post('email',0);

        $time = $time ? explode('~', $time) : [];
        $page = intval($this->post('page',1));

        $member_ids = [];

        $query = GuestHistoryLog::find()->where([
            'merchant_id'=>$this->getMerchantId()
        ]);

        if($group_id) {
            $query->andWhere(['chat_stype_id'=>$group_id]);
        }

        if($staff_id) {
            $query->andWhere(['staff_id'=>$staff_id]);
        }

        if($mobile) {
            $member = Member::find()
                ->asArray()
                ->where(['merchant_id'=>$this->getMerchantId(),'mobile'=>$mobile])
                ->all();

            $member_ids = !$member_ids ? array_column($member,'id') : [-1];
            $member_ids = array_intersect($member_ids, !$member ? [-1] : array_column($member,'id'));
        }

        if($email) {
            $member = Member::find()
                ->asArray()
                ->where(['merchant_id'=>$this->getMerchantId(),'email'=>$email])
                ->all();

            $member_ids = !$member_ids ? array_column($member,'id') : [-1];
            $member_ids = array_intersect($member_ids, !$member ? [-1] : array_column($member,'id'));
        }

        if($qq) {
            $member = Member::find()
                ->asArray()
                ->where(['merchant_id'=>$this->getMerchantId(),'qq'=>$qq])
                ->all();

            $member_ids = !$member_ids ? array_column($member,'id') : [-1];
            $member_ids = array_intersect($member_ids, !$member ? [-1] : array_column($member,'id'));
        }

        if($wechat) {
            $member = Member::find()
                ->asArray()
                ->where(['merchant_id'=>$this->getMerchantId(),'wechat'=>$wechat])
                ->all();

            $member_ids = !$member_ids ? array_column($member,'id') : [-1];
            $member_ids = array_intersect($member_ids, !$member ? [-1] : array_column($member,'id'));
        }

        if($member_ids) {
            $query->andWhere(['member_id'=>$member_ids]);
        }

        if($url) {
            $query->andWhere(['like','land_url',new Expression("'%$url%'")]);
        }

        if($time) {
            $query->andWhere(['>','created_time', trim($time[0])]);
            $query->andWhere(['<','created_time', trim($time[1])]);
        }

        $count = $query->count();

        $lists = $query->limit($this->page_size)
            ->offset(($page - 1) * $this->page_size)
            ->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        if($lists) {
            $staffs = ModelHelper::getDicByRelateID($lists, Staff::className(), 'cs_id', 'id',['name']);
            $member = ModelHelper::getDicByRelateID($lists, Member::className(), 'member_id','id',['name']);
            $style = ModelHelper::getDicByRelateID($lists, GroupChat::className(), 'chat_stype_id','id',['title']);

            foreach($lists as $key=>$history) {
                $history['staff_name'] = isset($staffs[$history['cs_id']])
                    ? $staffs[$history['cs_id']]['name']
                    : '暂无人员';

                $history['member_name']= isset($member[$history['member_id']])
                    ? $member[$history['member_id']]['name']
                    : '暂无';

                $history['style_title']= isset($style[$history['chat_stype_id']])
                    ? $style[$history['chat_stype_id']]['title']
                    : '普通风格';

                $lists[$key] = $history;
            }
        }

        // 转义字符.
        return $this->renderPageJSON(DataHelper::encodeArray($lists), '获取成功', $count);
    }

    /**
     * 获取当次的聊天的聊天记录.
     */
    public function actionChat()
    {
        $history_id = $this->post('history_id',0);

        if(!$history_id) {
            return $this->renderErrJSON('请选择正确的聊天记录.');
        }

        $history = GuestHistoryLog::findOne(['id'=>$history_id,'merchant_id'=>$this->getMerchantId()]);

        if(!$history) {
            return $this->renderErrJSON('请选择正确的历史记录');
        }

        // 开始获取信息.
        $guest_chat_log = GuestChatLog::find()
            ->where([
                'guest_log_id'  =>  $history_id,
                'merchant_id'   =>  $this->getMerchantId(),
            ])
            ->asArray()
            ->all();

        if($guest_chat_log) {
            foreach($guest_chat_log as $key=>$value) {
                $guest_chat_log[$key]['nickname'] = substr($value['uuid'], strlen($value['uuid']) - 12);
            }
        }

        return $this->renderJSON($guest_chat_log, '获取成功');
    }

    /**
     * 获取详细的聊天记录.
     */
    public function actionDetail()
    {
        $history_id = $this->post('history_id',0);

        if(!$history_id) {
            return $this->renderErrJSON('请选择正确的聊天记录');
        }

        $history = GuestHistoryLog::find()
            ->where(['id'=>$history_id,'merchant_id'=>$this->getMerchantId()])
            ->asArray()
            ->one();

        if(!$history) {
            return $this->renderErrJSON('请选择正确的聊天记录');
        }

        // 这里查询省市区.
        if(!$history['province_id']) {
            $history['province_str'] = '局域网访问';
        }else{
            $province = City::findOne(['id'=>$history['province_id']]);
            $city = City::findOne(['id'=>$history['city_id']]);

            $history['province_str'] = $province['name'] . ' ' . $city['name'];
        }

        return $this->renderJSON($history,'获取成功');
    }

    /**
     * 获取当前游客的历史轨迹.
     */
    public function actionHistory()
    {
        $history_id = $this->post('history_id',0);

        if(!$history_id) {
            return $this->renderErrJSON('请选择正确的聊天记录');
        }

        $history = GuestHistoryLog::findOne(['id'=>$history_id,'merchant_id'=>$this->getMerchantId()]);

        if(!$history) {
            return $this->renderErrJSON('请选择正确的聊天记录');
        }

        // 查询所有的聊天信息.
        $all_history = GuestHistoryLog::find()
            ->where([
                'uuid'  =>  $history['uuid'],
                'merchant_id'   =>  $this->getMerchantId()
            ])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();

        return $this->renderJSON($all_history,'获取成功');
    }
}
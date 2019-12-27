<?php
namespace www\modules\merchant\controllers\user;

use common\components\DataHelper;
use common\components\helper\ModelHelper;
use common\models\kefu\chat\GuestHistoryLog;
use common\models\merchant\GroupChat;
use common\models\merchant\Member;
use common\models\uc\Staff;
use www\modules\merchant\controllers\common\BaseController;

class TrackController extends BaseController
{
    public function actionIndex()
    {
        if($this->isGet()) {
            $group_id = $this->get('group_id',0);
            $time = $this->get('time','');
            $groups = GroupChat::findAll(['merchant_id'=>$this->getMerchantId()]);

            return $this->render('index',[
                'groups'    =>  $groups,
                'search_conditions' =>  [
                    'group_id'  =>  $group_id,
                    'time'      =>  $time
                ],
            ]);
        }

        $group_id = intval($this->post('group_id',0));
        $time = $this->post('time','');

        $time = $time ? explode('~', $time) : [];
        $page = intval($this->post('page',1));

        $query = GuestHistoryLog::find()->where([
            'merchant_id'=>$this->getMerchantId()
        ]);

        if($group_id) {
            $query->andWhere(['chat_stype_id'=>$group_id]);
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
}
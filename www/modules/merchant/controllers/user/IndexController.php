<?php
namespace www\modules\merchant\controllers\user;

use common\components\DataHelper;
use common\components\helper\ModelHelper;
use common\models\merchant\GroupChat;
use common\models\merchant\Member;
use common\models\uc\Staff;
use www\modules\merchant\controllers\common\BaseController;

class IndexController extends BaseController
{
    public function actionIndex()
    {
        if($this->isGet()) {
            return $this->render('index');
        }

        $page = intval($this->post('page',1));

        $query = Member::find()->where([
            'merchant_id'=>$this->getMerchantId()
        ]);

        $count = $query->count();

        $lists = $query->limit($this->page_size)
            ->offset(($page - 1) * $this->page_size)
            ->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        if($lists) {
            $staffs = ModelHelper::getDicByRelateID($lists, Staff::className(), 'cs_id', 'id',['name']);
            $style = ModelHelper::getDicByRelateID($lists, GroupChat::className(), 'chat_style_id','id',['title']);

            foreach($lists as $key=>$member) {
                $member['staff_name'] = isset($staffs[$member['cs_id']])
                    ? $staffs[$member['cs_id']]['name']
                    : '暂无人员';

                $member['style_title']= isset($style[$member['member_id']])
                    ? $style[$member['member_id']]['title']
                    : '暂无';

                $lists[$key] = $member;
            }
        }

        // 转义字符.
        return $this->renderPageJSON(DataHelper::encodeArray($lists), '获取成功', $count);
    }
}
<?php
namespace www\modules\merchant\controllers\user;

use common\components\helper\DataHelper;
use common\components\helper\ModelHelper;
use common\models\merchant\GuestHistoryLog;
use common\models\merchant\GuestChatLog;
use common\models\merchant\Member;
use common\models\uc\Staff;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

class MessageController extends BaseController
{
    /**
     * 消息内容.
     * @return string|\yii\console\Response|\yii\web\Response
     */
    public function actionIndex()
    {
        if($this->isGet()) {
            return $this->render('index');
        }

        $page = intval($this->post('page',1));

        $query = GuestChatLog::find()->where([
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
            $member = ModelHelper::getDicByRelateID($lists, Member::className(), 'member_id','id',['name']);
            $guest_log = ModelHelper::getDicByRelateID($lists, GuestHistoryLog::className(), 'guest_log_id','id',['created_time']);

            foreach($lists as $key=>$message) {
                $message['staff_name'] = isset($staffs[$message['cs_id']])
                    ? $staffs[$message['cs_id']]['name']
                    : '暂无人员';

                $message['member_name']= isset($member[$message['member_id']])
                    ? $member[$message['member_id']]['name']
                    : '暂无';

                $message['access_time']= isset($guest_log[$message['guest_log_id']])
                    ? $guest_log[$message['guest_log_id']]['created_time']
                    : ConstantService::$default_datetime;

                $lists[$key] = $message;
            }
        }

        // 转义字符.
        return $this->renderPageJSON(DataHelper::encodeArray($lists), '获取成功', $count);
    }
}
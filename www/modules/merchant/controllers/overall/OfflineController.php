<?php
namespace www\modules\merchant\controllers\overall;

use common\components\DataHelper;
use common\models\merchant\GroupChat;
use common\models\merchant\LeaveMessage;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

/**
 * 留言管理.
 * Class OfflineController
 * @package www\modules\merchant\controllers\overall
 */
class OfflineController extends BaseController
{
    /**
     * 离线表单.
     * @return string
     */
    public function actionIndex()
    {
        if($this->isGet()) {
            return $this->render('index', [
                'keyword'   =>  trim($this->get('keyword'))
            ]);
        }

        $page = intval($this->post('page',1));

        $keyword = trim($this->post('keyword',''));

        $query = LeaveMessage::find()->where(['merchant_id'=>$this->getMerchantId()]);

        if($keyword) {
            $query->andWhere(['mobile'=>$keyword]);
        }

        $count = $query->count();

        $lists = $query->limit($this->page_size)
            ->offset(($page - 1) * $this->page_size)
            ->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        if($lists) {
            $groups = DataHelper::getDicByRelateID($lists, GroupChat::className(), 'group_chat_id','id');

            foreach($lists as $key => $message) {
                $message['group_chat'] = isset($groups[$message['group_chat_id']])
                    ? $groups[$message['group_chat_id']]['title']
                    : '普通风格';

                $lists[$key] = $message;
            }
        }

        // 转义字符.
        return $this->renderPageJSON(DataHelper::encodeArray($lists), '获取成功', $count);
    }

    /**
     * 信息保存.
     */
    public function actionSave()
    {
        $id = intval($this->post('id',0));

        if(!$id) {
            return $this->renderErrJSON('非法请求' );
        }

        $message = LeaveMessage::findOne([
            'id'    =>  $id,
            'status'=>  ConstantService::$default_status_false,
            'merchant_id'   =>  $this->getMerchantId()
        ]);

        if(!$message) {
            return $this->renderErrJSON( '该条留言已经被处理了~~' );
        }

        $message->setAttribute('status',ConstantService::$default_status_true);

        if(!$message->save(0)) {
            return $this->renderErrJSON( '数据库保存失败,请联系管理员' );
        }

        return $this->renderJSON([],'操作成功');
    }
}
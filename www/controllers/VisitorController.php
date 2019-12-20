<?php
namespace www\controllers;

use common\models\merchant\GuestChatLog;
use common\services\uc\MerchantService;
use www\controllers\common\BaseController;

class VisitorController extends BaseController
{
    /**
     * 获取游客跟自己的聊天记录.
     */
    public function actionMessage()
    {
        // 最后一次聊天的时间.
        $last_time = $this->post('last_time',date('Y-m-d H:i:s'));

        $msn = $this->get('msn','');

        $uuid = $this->getGuestUUID();

        if(!$uuid) {
            return $this->renderErrJSON('非法的用户信息');
        }

        if(!$msn) {
            return $this->renderErrJSON('请选择正确的商户信息');
        }

        $merchant = MerchantService::getInfoBySn($this->get('msn',''));

        if(!$merchant) {
            return $this->renderErrJSON('非法的商户信息');
        }

        $guest_chat_log = GuestChatLog::find()
            ->where(['<','created_time', $last_time])
            ->andWhere(['merchant_id'=>$merchant['id'],'uuid'=> $this->getGuestUUID()])
            ->orderBy(['id'=>SORT_DESC])
            ->limit($this->page_size)
            ->asArray()
            ->select(['id','cs_id','uuid','from_id','content','created_time'])
            ->all();

        return $this->renderJSON($guest_chat_log,'获取成功');
    }
}
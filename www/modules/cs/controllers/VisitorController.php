<?php
namespace www\modules\cs\controllers;

use common\components\helper\DateHelper;
use common\models\merchant\BlackList;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
use common\services\QueueListService;
use www\modules\cs\controllers\common\BaseController;

class VisitorController extends BaseController
{
    /**
     * 删除游客. 强制关闭.
     */
    public function actionRemove()
    {
        $uuid = $this->post('uuid','');

        if(!$uuid) {
            return $this->renderErrJSON('请选择正确的游客ID');
        }

        // 如果是正确的.这里就要关闭游客和游客对应的链接.
        // @todo 先直接关闭吧.
        QueueListService::push2Guest(QueueConstant::$queue_guest_chat,[
            'cmd'   =>  ConstantService::$chat_cmd_close_guest,
            'f_id'  =>  $this->current_user['sn'],
            't_id'  =>  $uuid
        ]);

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

        // 这里要查询一次.得到游客的ID.
        $black = new BlackList();

        $black->setAttributes([
            'ip'            =>  '',
            'merchant_id'   =>  $this->merchant_info['id'],
            'visitor_id'    =>  $uuid,
            'staff_id'      =>  $this->getStaffId(),
            'status'        =>  1,
            'expired_time'  =>  DateHelper::getFormatDateTime('Y-m-d H:i:s', strtotime('+10 year')),
        ],0);

        if(!$black->save(0)) {
            return $this->renderErrJSON('黑名单信息保存失败');
        }

        QueueListService::push2Guest(QueueConstant::$queue_guest_chat, [
            'cmd'   =>  ConstantService::$chat_cmd_close_guest,
            'f_id'  =>  $this->current_user['sn'],
            't_id'  =>  $uuid
        ]);

        return $this->renderJSON('操作成功');
    }
}
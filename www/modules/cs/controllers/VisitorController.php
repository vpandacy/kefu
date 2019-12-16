<?php
namespace www\modules\cs\controllers;

use common\components\helper\DateHelper;
use common\components\helper\ValidateHelper;
use common\models\merchant\BlackList;
use common\services\constant\QueueConstant;
use common\services\ConstantService;
use common\services\QueueListService;
use www\modules\cs\controllers\common\BaseController;

/**
 * @todo 先暂时把坑给占着.后续需要将这些全部完善好.
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
        // @todo 先直接关闭吧.
        $ret = QueueListService::push2Guest(QueueConstant::$queue_guest_chat,[
            'cmd'   =>  ConstantService::$chat_cmd_close_guest,
            'data'  =>  [
                'f_id'  =>  $this->current_user['sn'],
                't_id'  =>  $uuid
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
            'data'  =>  [
                'f_id'  =>  $this->current_user['sn'],
                't_id'  =>  $uuid
            ],
        ]);

        return $this->renderJSON('操作成功');
    }

    /**
     * 保存游客数据.
     */
    public function actionSave()
    {
        $data = $this->post(null);

        $request_r = ['name','mobile','email','qq','wechat'];

        if(count(array_intersect($request_r,array_keys($data))) != count($request_r)) {
            return $this->renderErrJSON('参数非法');
        }

        if($data['name'] && !ValidateHelper::validLength($data['name'],1,255)) {
            return $this->renderErrJSON('请输入正确长度的名称');
        }

        if($data['qq'] && !ValidateHelper::validLength($data['qq'],1,13)) {
            return $this->renderErrJSON('请输入正确长度的ＱＱ号');
        }

        if($data['mobile'] && !ValidateHelper::validMobile($data['mobile'])) {
            return $this->renderErrJSON('请输入正确的手机号');
        }

        if($data['email'] && !ValidateHelper::validEmail($data['email'])) {
            return $this->renderErrJSON('请输入正确格式的邮箱');
        }
        // 开始保存信息.

        // 这里要处理保存的逻辑.
        return $this->renderJSON('保存成功');
    }
}
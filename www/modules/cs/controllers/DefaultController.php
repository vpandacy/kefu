<?php

namespace www\modules\cs\controllers;

use common\models\merchant\CommonWord;
use common\models\uc\Staff;
use common\services\chat\ChatGroupService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\monitor\WSCenterService;
use www\modules\cs\controllers\common\BaseController;

class DefaultController extends BaseController
{
    /**
     * 默认进入即上线.
     * @return string
     */
    public function actionIndex()
    {
        $current_info = $this->current_user;

        // 要获取常用语信息.
        $words = CommonWord::find()
            ->where([
                'merchant_id'   =>  $current_info['merchant_id'],
                'status'        =>  ConstantService::$default_status_true,
            ])
            ->asArray()
            ->select(['id','words'])
            ->all();

        $staff = Staff::findOne(['id'=>$this->current_user['id']]);
        $staff['is_online'] = 1;

        if($staff->save() === false) {
            return $this->redirect(GlobalUrlService::buildUcUrl('/default/forbidden'));
        }

        // 要获取当前在线的用户数量和等待数量.
        $online_users = ChatGroupService::getGroupAllUsers($this->current_user['sn']);
        // 这里是游客等待区.
        $offline_users = ChatGroupService::getWaitGroupAllUsers($this->current_user['sn']);

        return $this->render('index', [
            'staff' => $this->current_user,
            'words' =>  $words,
            'online_users'  =>  $online_users,
            'offline_users' =>  $offline_users,
            'js_params' => [
                'ws' => WSCenterService::getCSWSByRoute( $current_info['id'] ),
                'sn' => $current_info['sn'],
                'msn'=> $this->merchant_info['sn'],
            ],
        ]);
    }

    public function actionForbidden()
    {
        $this->layout = false;
        return $this->render('forbidden');
    }
}

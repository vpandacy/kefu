<?php
namespace www\modules\merchant\controllers\overall;

use common\components\DataHelper;
use common\models\merchant\GroupChat;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

class CodeController extends BaseController
{
    /**
     * 获取客服代码.
     * @return string
     */
    public function actionIndex()
    {
        // 获取所有的风格分组.
        $group_chats = GroupChat::find()
            ->where([
                'status'    =>  ConstantService::$default_status_true,
                'merchant_id'   =>  $this->getMerchantId(),
            ])
            ->select(['id','title'])
            ->asArray()
            ->all();

        $current_code = $this->get('code',0);

        $group = $current_code ? GroupChat::findOne(['id' => $current_code]) : new GroupChat();

        return $this->render('index',[
            'groups'    =>  $group_chats,
            'current'   =>  $group,
            'code'      =>  DataHelper::encode($this->renderPartial('obtain',['group_sn'=>$group['sn']])),
        ]);
    }

    /**
     * 获取客服的js代码.
     */
    public function actionObtain()
    {
        $group_id = $this->post('group_id', 0);

        $group = GroupChat::findOne(['id'=>$group_id,'status' => ConstantService::$default_status_true]);

        if(!$group && $group_id) {
            return $this->renderErrJSON( '该风格不存在' );
        }

        $code = $this->renderPartial('obtain',[
            'group_sn'  =>  $group['sn'],
        ]);

        return $this->renderJSON($code,'获取成功');
    }
}
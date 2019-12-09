<?php

namespace www\modules\merchant\controllers\style;

use common\models\merchant\GroupChat;
use common\services\CommonService;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

/**
 * Default controller for the `merchant` module
 */
class IndexController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取风格列表页.
     */
    public function actionList()
    {
        $query = GroupChat::find();

        $lists = $query->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        return $this->renderPageJSON($lists, $query->count(), 0);
    }

    /**
     * 编辑或添加
     * @return string|\yii\web\Response
     */
    public function actionEdit()
    {
        $group_id = intval($this->get('group_id',0));

        if($group_id > 0) {
            $group = GroupChat::find()
                ->where([
                    'id'=>$group_id,
                    'merchant_id'=>$this->getMerchantId(),
                ])
                ->one();
        }else{
            $group = new GroupChat();
        }

        if($group_id && !$group) {
            // 返回回去.
            return $this->responseFail('您暂无权限操作');
        }

        return $this->render('edit',[
            'group' =>  $group,
        ]);
    }

    /**
     * 信息保存.
     */
    public function actionSave()
    {
        $data = $this->post(null);

        $request_r = ['title','desc', 'id'];

        if(count(array_intersect(array_keys($data), $request_r)) != count($request_r)) {
            return $this->renderJSON([],'参数丢失', ConstantService::$response_code_fail);
        }

        if(!$data['title'] || mb_strlen($data['title']) > 255) {
            return $this->renderJSON([],'请输入正确的风格名称', ConstantService::$response_code_fail);
        }

        if(!$data['desc'] || mb_strlen($data['desc']) > 255) {
            return $this->renderJSON([],'请输入正确的简历', ConstantService::$response_code_fail);
        }

        if($data['id'] > 0) {
            $group = GroupChat::find()
                ->where([
                    'id'=>$data['id'],
                    'merchant_id'=>$this->getMerchantId(),
                ])
                ->one();
        }else{
            $group = new GroupChat();
        }

        if($data['id'] > 0 && !$group['id']) {
            return $this->renderJSON([],'非法请求', ConstantService::$response_code_fail);
        }

        if(!$data['id']) {
            $data['sn'] = CommonService::genUniqueName();
            $data['merchant_id'] = $this->getMerchantId();
            $data['status'] = ConstantService::$default_status_true;
        }

        unset($data['id']);

        $group->setAttributes($data,0);

        if(!$group->save(0)) {
            return $this->renderJSON([],'数据库保存失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'操作成功', ConstantService::$response_code_success);
    }

    /**
     * 恢复.
     */
    public function actionRecover()
    {
        $ids = $this->post('ids');

        if(!count($ids)) {
            return $this->renderJSON([],'请选择需要恢复的风格', ConstantService::$response_code_fail);
        }

        if(!GroupChat::updateAll(['status' => ConstantService::$default_status_true],[ 'and',
            ['id'=>$ids,'merchant_id'=> $this->getMerchantId()],
        ])) {
            return $this->renderJSON([],'恢复失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'恢复成功', ConstantService::$response_code_success);
    }

    /**
     * 禁用.
     */
    public function actionDisable()
    {
        $id = $this->post('id',0);
        if(!$id || !is_numeric($id)) {
            return $this->renderJSON([],'请选择正确的帐号', ConstantService::$response_code_fail);
        }

        $group = GroupChat::find()
            ->where([
                'id'=>$id,
                'merchant_id'=>$this->getMerchantId(),
                'status'=>ConstantService::$default_status_true
            ])
            ->one();;

        if($group['status'] != ConstantService::$default_status_true) {
            return $this->renderJSON([],'该风格不需要禁用', ConstantService::$response_code_fail);
        }

        $group['status'] = 0;
        if(!$group->save(0)) {
            return $this->renderJSON([],'操作失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'操作成功', ConstantService::$response_code_success);
    }
}
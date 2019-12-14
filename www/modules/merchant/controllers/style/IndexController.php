<?php

namespace www\modules\merchant\controllers\style;

use common\components\DataHelper;
use common\components\helper\ValidateHelper;
use common\models\merchant\GroupChat;
use common\models\merchant\GroupChatStaff;
use common\models\uc\Department;
use common\models\uc\Staff;
use common\services\CommonService;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

/**
 * Default controller for the `merchant` module
 */
class IndexController extends BaseController
{
    /**
     * 风格列表页
     * @return string
     */
    public function actionIndex()
    {
        if($this->isGet()) {
            return $this->render('index');
        }

        $page = intval($this->post('page',1));

        $query = GroupChat::find()->where(['merchant_id'=>$this->getMerchantId()]);

        $total = $query->count();

        $lists = $query->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->limit($this->page_size)
            ->offset(($page - 1) * $this->page_size )
            ->all();

        return $this->renderPageJSON($lists, $query->count(), $total);
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
            return $this->renderErrJSON( '参数丢失' );
        }

        if(!ValidateHelper::validLength($data['title'], 1, 255)) {
            return $this->renderErrJSON( '请输入正确的风格名称' );
        }

        if(!ValidateHelper::validLength($data['desc'], 1, 255)) {
            return $this->renderErrJSON( '请输入正确的简历' );
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
            return $this->renderErrJSON( '非法请求' );
        }

        if(!$data['id']) {
            $data['sn'] = CommonService::genUniqueName();
            $data['merchant_id'] = $this->getMerchantId();
            $data['status'] = ConstantService::$default_status_true;
        }

        unset($data['id']);

        $group->setAttributes($data,0);

        if(!$group->save(0)) {
            return $this->renderErrJSON( '数据库保存失败,请联系管理员' );
        }

        return $this->renderJSON([],'操作成功');
    }

    /**
     * 恢复.
     */
    public function actionRecover()
    {
        $ids = $this->post('ids');

        if(!count($ids)) {
            return $this->renderErrJSON( '请选择需要恢复的风格' );
        }

        if(!GroupChat::updateAll(['status' => ConstantService::$default_status_true],[ 'and',
            ['id'=>$ids,'merchant_id'=> $this->getMerchantId()],
        ])) {
            return $this->renderErrJSON( '恢复失败,请联系管理员' );
        }

        return $this->renderJSON([],'恢复成功');
    }

    /**
     * 禁用.
     */
    public function actionDisable()
    {
        $id = $this->post('id',0);
        if(!$id || !is_numeric($id)) {
            return $this->renderErrJSON( '请选择正确的帐号' );
        }

        $group = GroupChat::find()
            ->where([
                'id'=>$id,
                'merchant_id'=>$this->getMerchantId(),
                'status'=>ConstantService::$default_status_true
            ])
            ->one();

        if($group['status'] != ConstantService::$default_status_true) {
            return $this->renderErrJSON( '该风格不需要禁用' );
        }

        $group['status'] = 0;
        if(!$group->save(0)) {
            return $this->renderErrJSON('操作失败,请联系管理员');
        }

        return $this->renderJSON([],'操作成功');
    }

    /**
     * 客服分配.
     * @return string|\yii\web\Response
     */
    public function actionAssign()
    {
        $group_id = intval($this->get('group_id',0));

        if(!$group_id || !is_numeric($group_id)) {
            return $this->responseFail('非法的ID');
        }

        $group = GroupChat::find()
            ->where([
                'id'=>$group_id,
                'merchant_id'=>$this->getMerchantId(),
            ])
            ->one();

        if(!$group) {
            return $this->responseFail('未找到该风格');
        }

        $staff = Staff::find()
            ->andWhere(['merchant_id'=>$this->getMerchantId()])
            ->andWhere(['like', 'app_ids', '%,'.$this->getAppId() . ',%', false])
            ->asArray()
            ->all();

        if($staff) {
            $departments = DataHelper::getDicByRelateID($staff, Department::className(), 'department_id', 'id');

            foreach($staff as $key=>$employee) {
                $employee['department'] = isset($departments[$employee['department_id']])
                    ? $departments[$employee['department_id']]['name']
                    : '暂无部门';

                $staff[$key] = $employee;
            }
        }

        $data = [];
        // 开始分组.
        foreach($staff as $row) {
            if(!$row['department_id']) {
                $row['department_id'] = 0;
            }

            $data[$row['department_id']][] = $row;
        }

        $staff_ids = GroupChatStaff::find()
            ->where([
                'group_chat_id' =>  $group_id,
                'merchant_id'   =>  $this->getMerchantId(),
                'status'        =>  ConstantService::$default_status_true
            ])
            ->select(['staff_id'])
            ->column();

        return $this->render('assign',[
            'group' =>  $group,
            'data'  =>  $data,
            'staff_ids' =>  $staff_ids,
        ]);
    }

    /**
     * 保存分配的信息.
     */
    public function actionDistribution()
    {
        $group_id = $this->post('group_id', 0);

        if(!$group_id) {
            return $this->renderErrJSON( '非法请求' );
        }

        $group = GroupChat::find()
            ->where([
                'id'=>$group_id,
                'merchant_id'=>$this->getMerchantId(),
            ])
            ->one();

        if(!$group) {
            return $this->renderErrJSON('未找到该风格');
        }

        $staff_ids = $this->post('staff_ids');

        if(count($staff_ids) <= 0) {
            return $this->renderErrJSON( '请选择正确的员工' );
        }

        $owner_staff_ids = Staff::find()
            ->andWhere(['merchant_id'=>$this->getMerchantId()])
            ->andWhere(['like', 'app_ids', '%,'.$this->getAppId() . ',%', false])
            ->select(['id'])
            ->column();

        if(array_diff($staff_ids, $owner_staff_ids)) {
            return $this->renderErrJSON( '请选择正确的员工信息' );
        }

        // 开始保存信息.
        if(GroupChatStaff::updateAll(['status'=>ConstantService::$default_status_false],['group_chat_id'=>$group_id]) === false){
            return $this->renderErrJSON( '数据库保存失败,请联系管理员' );
        }

        $data = [];
        foreach($staff_ids as $staff_id) {
            $data[] = [
                'merchant_id'   =>  $this->getMerchantId(),
                'group_chat_id' =>  $group_id,
                'staff_id'      =>  $staff_id,
                'status'        =>  ConstantService::$default_status_true
            ];
        }

        $ret = GroupChatStaff::getDb()->createCommand()
            ->batchInsert(GroupChatStaff::tableName(), ['merchant_id','group_chat_id','staff_id','status'], $data)
            ->execute();

        if(!$ret) {
            return $this->renderErrJSON( '数据库保存失败,请联系管理员' );
        }

        return $this->renderJSON([],'保存成功');
    }
}
<?php

namespace uc\controllers;

use common\components\DataHelper;
use common\models\uc\Role;
use common\models\uc\StaffRole;
use common\models\uc\Department;
use common\models\uc\Staff;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\uc\RoleService;
use uc\controllers\common\BaseController;

/**
 * Default controller for the `merchant` module
 */
class StaffController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $departments = Department::find()
            ->where([
                'status'    =>  ConstantService::$default_status_true,
                'merchant_id'   =>  $this->getMerchantId(),
                'app_id'    =>  $this->getAppId(),
            ])
            ->select(['id','name'])
            ->asArray()
            ->all();


        return $this->render('index',[
            'departments'   =>  $departments,
            'search_conditions' =>  [
                'mobile'    =>  trim($this->get('mobile','')),
                'email'     =>  trim($this->get('email','')),
                'department_id' =>  trim($this->get('department_id',0)),
            ]
        ]);
    }

    /**
     * 获取字段信息
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionList()
    {
        $page = intval($this->get('page',1));

        $query = Staff::find()->andWhere(['merchant_id'=>$this->getMerchantId()]);
        // 构建条件
        $mobile = trim($this->get('mobile',''));
        $email  = trim($this->get('email',''));
        $department_id = $this->get('department_id',0);

        if($mobile) {
            $query->andWhere(['mobile'=>$mobile]);
        }

        if($email) {
            $query->andWhere(['email'=>$email]);
        }

        if($department_id) {
            $query->andWhere(['department_id'=>$department_id]);
        }

        $query->andWhere(['like', 'app_ids', '%,'.$this->getAppId() . ',%', false]);

        $count = $query->count();

        $employees = $query->limit($this->page_size)
            ->offset(($page - 1) * $this->page_size)
            ->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        if($employees) {
            $departments = DataHelper::getDicByRelateID($employees, Department::className(), 'department_id', 'id');

            foreach($employees as $key=>$employee) {
                $employee['department'] = isset($departments[$employee['department_id']])
                    ? $departments[$employee['department_id']]['name']
                    : '暂无部门';

                $employees[$key] = $employee;
            }
        }

        return $this->renderPageJSON($employees, '获取成功', $count);
    }

    /**
     * 编辑或添加界面.
     * @return string|\yii\web\Response
     */
    public function actionEdit()
    {
        $staff_id = intval($this->get('staff_id',0));

        if($staff_id > 0) {
            $staff = Staff::find()
                ->where([
                    'id'=>$staff_id,
                    'merchant_id'=>$this->getMerchantId(),
                ])
                ->andWhere(['like', 'app_ids', '%,'.$this->getAppId() . ',%', false])
                ->one();
        }else{
            $staff = new Staff();
        }

        if($staff_id && !$staff) {
            // 返回回去.
            return $this->responseFail('您暂无权限操作');
        }

        $departments = Department::find()
            ->where([
                'status'    =>  ConstantService::$default_status_true,
                'merchant_id'   =>  $this->getMerchantId(),
                'app_id'    =>  $this->getAppId(),
            ])
            ->select(['id','name'])
            ->asArray()
            ->all();

        // 所有角色.
        $roles = Role::find()
            ->where([
                'status'    =>  ConstantService::$default_status_true,
                'merchant_id'   =>  $this->getMerchantId(),
                'app_id'    =>  $this->getAppId(),
            ])
            ->select(['id','name'])
            ->asArray()
            ->all();

        $role_ids = StaffRole::find()
            ->where([
                'status' => ConstantService::$default_status_true,
                'staff_id' => $staff['id'],
                'app_id'    =>  $this->getAppId(),
            ])
            ->select(['role_id'])
            ->column();

        return $this->render('edit',[
            'staff' =>  $staff,
            'departments'    => $departments,
            'roles' =>  $roles,
            'role_ids'  =>  $role_ids,
        ]);
    }

    /**
     * 信息保存.
     */
    public function actionSave()
    {
        $data = $this->post(null);

        $request_r = ['mobile','email','name','listen_nums','department_id','avatar','password','confirm_password','id'];

        if(count(array_intersect(array_keys($data), $request_r)) != count($request_r)) {
            return $this->renderJSON([],'参数丢失', ConstantService::$response_code_fail);
        }

        // 开始判断.
        if(!preg_match('/^1\d{10}/', $data['mobile'])) {
            return $this->renderJSON([],'请输入正确的手机号', ConstantService::$response_code_fail);
        }

        if(strpos($data['email'],'@') <= 1) {
            return $this->renderJSON([],'请输入正确的手机号', ConstantService::$response_code_fail);
        }

        if(!$data['name'] || mb_strlen($data['name']) > 255) {
            return $this->renderJSON([],'请输入正确的姓名/商户名', ConstantService::$response_code_fail);
        }

        if($data['listen_nums'] < 0 || !is_numeric($data['listen_nums'])) {
            return $this->renderJSON([],'请输入正确的接听数', ConstantService::$response_code_fail);
        }

        $departments = Department::find()
            ->where([
                'status'    =>  ConstantService::$default_status_true,
                'merchant_id'   =>  $this->getMerchantId(),
                'app_id'    =>  $this->getAppId(),
            ])
            ->select(['id'])
            ->column();

        if($data['department_id'] && !in_array($data['department_id'], $departments)) {
            return $this->renderJSON([],'请选择正确的部门', ConstantService::$response_code_fail);
        }

        if($data['password'] && $data['password'] != $data['confirm_password']) {
            return $this->renderJSON([],'两次输入的密码不一致,请重新输入', ConstantService::$response_code_fail);
        }

        $role_ids = Role::find()
            ->where([
                'status'=>ConstantService::$default_status_true,
                'merchant_id'=>$this->getMerchantId(),
                'app_id'    =>  $this->getAppId(),
            ])
            ->select(['id'])
            ->column();

        if(array_key_exists('role_ids', $data) && array_diff($data['role_ids'], $role_ids)) {
            return $this->renderJSON([],'请选择正确的角色', ConstantService::$response_code_fail);
        }

        // 检查密码强度.
        if($data['password'] && !CommonService::checkPassLevel($data['password'])) {
            return $this->renderJSON([], CommonService::getLastErrorMsg(), ConstantService::$response_code_fail);
        }

        if(!$data['id'] && !$data['password']) {
            return $this->renderJSON([],'新增帐号时请输入密码', ConstantService::$response_code_fail);
        }

        if($data['id'] > 0) {
            $staff = Staff::find()
                ->where([
                    'id'=>$data['id'],
                    'merchant_id'=>$this->getMerchantId(),
                    'status'=>ConstantService::$default_status_true
                ])
                ->andWhere(['like', 'app_ids', '%,'.$this->getAppId() . ',%', false])
                ->one();
        }else{
            $staff = new Staff();
        }

        if($data['id'] > 0 && !$staff['id']) {
            return $this->renderJSON([],'非法的员工', ConstantService::$response_code_fail);
        }

        if(!$staff && Staff::findOne(['email'=>$data['email']])) {
            return $this->renderJSON([],'该邮箱已经被使用了,请稍后重新添加', ConstantService::$response_code_fail);
        }

        if(!$data['id']) {
            $data['sn'] = CommonService::genUniqueName();
            $data['merchant_id'] = $this->getMerchantId();
            $data['status'] = ConstantService::$default_status_true;
            $staff['salt'] = CommonService::genUniqueName();
            // 设置应用ID.
            $data['app_id'] = ',' . $this->getAppId() . ',';
        }

        if($data['password']) {
            $data['password'] = $this->genPassword($this->getMerchantId(), $data['password'], $staff['salt']);
        }else{
            unset($data['password']);
        }

        $staff->setAttributes($data,0);

        if(!$staff->save(0)) {
            return $this->renderJSON([],'数据库保存失败,请联系管理员', ConstantService::$response_code_fail);
        }

        if(array_key_exists('role_ids', $data) && !RoleService::createRoleMapping($staff['id'], $this->getAppId(),$data['role_ids'])) {
            return $this->renderJSON([],RoleService::getLastErrorMsg(), ConstantService::$response_code_fail);
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
            return $this->renderJSON([],'请选择需要恢复的帐号', ConstantService::$response_code_fail);
        }

        if(!Staff::updateAll(['status' => ConstantService::$default_status_true],[ 'and',
            ['id'=>$ids,'merchant_id'=> $this->getMerchantId()],
            ['like', 'app_ids', '%,'.$this->getAppId() . ',%', false]
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

        if($id == $this->getStaffId()) {
            return $this->renderJSON([],'您暂不能禁用自己', ConstantService::$response_code_fail);
        }

        $staff = Staff::find()
            ->where([
                'id'=>$id,
                'merchant_id'=>$this->getMerchantId(),
                'status'=>ConstantService::$default_status_true
            ])
            ->andWhere(['like', 'app_ids', '%,'.$this->getAppId() . ',%', false])
            ->one();;

        if($staff['status'] != ConstantService::$default_status_true) {
            return $this->renderJSON([],'该帐号不需要删除', ConstantService::$response_code_fail);
        }

        $staff['status'] = 0;
        if(!$staff->save(0)) {
            return $this->renderJSON([],'操作失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'操作成功', ConstantService::$response_code_success);
    }
}

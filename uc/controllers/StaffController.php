<?php

namespace uc\controllers;

use common\components\helper\ModelHelper;
use common\components\helper\ValidateHelper;
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
        if($this->isGet()) {
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

        $page = intval($this->post('page',1));

        $query = Staff::find()->andWhere(['merchant_id'=>$this->getMerchantId()]);
        // 构建条件
        $mobile = trim($this->post('mobile',''));
        $email  = trim($this->post('email',''));
        $department_id = $this->post('department_id',0);

        if($mobile) {
            $query->andWhere(['mobile'=>$mobile]);
        }

        if($email) {
            $query->andWhere(['email'=>$email]);
        }

        if($department_id) {
            $query->andWhere(['department_id'=>$department_id]);
        }

//        $query->andWhere(['like', 'app_ids', '%,'.$this->getAppId() . ',%', false]);

        $count = $query->count();

        $employees = $query->limit($this->page_size)
            ->offset(($page - 1) * $this->page_size)
            ->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        if($employees) {
            $departments = ModelHelper::getDicByRelateID($employees, Department::className(), 'department_id', 'id',['name']);
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

        $request_r = [
            'mobile', 'email', 'nickname','name','listen_nums',
            'department_id','avatar','password','confirm_password','id'
        ];

        if(count(array_intersect(array_keys($data), $request_r)) != count($request_r)) {
            return $this->renderErrJSON( '参数丢失' );
        }

        // 开始判断.
        if(!ValidateHelper::validLength($data['nickname'], 1, 255)) {
            return $this->renderErrJSON( '请输入正确长度的昵称' );
        }

        // 开始判断.
        if(!ValidateHelper::validMobile($data['mobile'])) {
            return $this->renderErrJSON( '请输入正确的手机号' );
        }

        if(!ValidateHelper::validEmail($data['email'])) {
            return $this->renderErrJSON( '请输入正确的手机号' );
        }

        if(!ValidateHelper::validLength($data['name'], 1, 255)) {
            return $this->renderErrJSON( '请输入正确的姓名/商户名' );
        }

        if($data['listen_nums'] < 0 || !is_numeric($data['listen_nums'])) {
            return $this->renderErrJSON( '请输入正确的接听数' );
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
            return $this->renderErrJSON( '请选择正确的部门' );
        }

        if($data['password'] && $data['password'] != $data['confirm_password']) {
            return $this->renderErrJSON( '两次输入的密码不一致,请重新输入' );
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
            return $this->renderErrJSON( '请选择正确的角色' );
        }

        // 检查密码强度.
        if($data['password'] && !CommonService::checkPassLevel($data['password'])) {
            return $this->renderErrJSON( CommonService::getLastErrorMsg() );
        }

        if(!$data['id'] && !$data['password']) {
            return $this->renderErrJSON( '新增帐号时请输入密码' );
        }

        $other_staff = Staff::find()
            ->where([
                'mobile'=>$data['mobile']
            ])
            ->andWhere(['!=','id',$data['id']])
            ->one();

        // 这里还需要检查一下mobile.
        if($other_staff) {
            return $this->renderErrJSON('您更换的手机号已经被别人所使用了');
        }

        $other_staff = Staff::find()
            ->where([
                'email'=>$data['email']
            ])
            ->andWhere(['!=','id',$data['id']])
            ->one();

        // 这里还需要检查一下mobile.
        if($other_staff) {
            return $this->renderErrJSON('您添加的邮箱已经被其他人所使用了');
        }

        if($data['id'] > 0) {
            $staff = Staff::find()
                ->where([
                    'id'=>$data['id'],
                    'merchant_id'=>$this->getMerchantId(),
                ])
                ->andWhere(['like', 'app_ids', '%,'.$this->getAppId() . ',%', false])
                ->one();
        }else{
            $staff = new Staff();
        }

        if($data['id'] > 0 && !$staff['id']) {
            return $this->renderErrJSON( '非法的员工' );
        }

        if(!$staff && Staff::findOne(['email'=>$data['email']])) {
            return $this->renderErrJSON( '该邮箱已经被使用了,请稍后重新添加' );
        }

        if(!$data['id']) {
            $data['sn'] = CommonService::genUniqueName();
            $data['merchant_id'] = $this->getMerchantId();
            $data['status'] = ConstantService::$default_status_true;
            $data['salt'] = CommonService::genUniqueName();
            $data['avatar'] = ConstantService::$default_avatar;
            $data['is_login'] = ConstantService::$default_status_false;
            // 设置应用ID.
            $data['app_ids'] = ',' . $this->getAppId() . ',';
        }

        if($data['password']) {
            $data['password'] = $this->genPassword($this->getMerchantId(), $data['password'], $staff['salt']);
            $data['is_login'] = ConstantService::$default_status_false;
            $data['is_online'] = ConstantService::$default_status_false;
        }else{
            unset($data['password']);
        }

        $staff->setAttributes($data,0);

        if(!$staff->save(0)) {
            return $this->renderErrJSON( '数据库保存失败,请联系管理员' );
        }

        if(array_key_exists('role_ids', $data) && !RoleService::createRoleMapping($staff['id'], $this->getAppId(),$data['role_ids'])) {
            return $this->renderErrJSON( RoleService::getLastErrorMsg() );
        }

        if($data['password']) {
            $cookie = \Yii::$app->params['cookies']['staff'];
            // 删除cookie
            $this->removeCookie($cookie['name'], $cookie['domain']);
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
            return $this->renderErrJSON( '请选择需要恢复的帐号' );
        }

        if(!Staff::updateAll(['status' => ConstantService::$default_status_true],[ 'and',
            ['id'=>$ids,'merchant_id'=> $this->getMerchantId()],
            ['like', 'app_ids', '%,'.$this->getAppId() . ',%', false]
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

        if($id == $this->getStaffId()) {
            return $this->renderErrJSON( '您暂不能禁用自己' );
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
            return $this->renderErrJSON( '该帐号不需要删除' );
        }

        $staff['status'] = 0;
        if(!$staff->save(0)) {
            return $this->renderErrJSON( '操作失败,请联系管理员' );
        }

        return $this->renderJSON([],'操作成功');
    }
}

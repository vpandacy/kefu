<?php

namespace uc\controllers;

use common\components\helper\DataHelper;
use common\components\helper\ModelHelper;
use common\components\helper\UtilHelper;
use common\components\helper\ValidateHelper;
use common\models\uc\Role;
use common\models\uc\StaffRole;
use common\models\uc\Department;
use common\models\uc\Staff;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\uc\RoleService;
use uc\controllers\common\BaseController;

class StaffController extends BaseController
{
    public function actionIndex()
    {
        $kw = trim($this->get('kw', ''));
        $department_id = intval(trim($this->get('department_id', 0)));

        $p = $this->get("p", 1);
        $p = ($p > 0) ? $p : 1;
        $offset = ($p - 1) * $this->page_size;
        $query = Staff::find()->andWhere(['merchant_id' => $this->getMerchantId()]);

        if( $department_id ){
            $query = $query->andWhere([ "department_id" => $department_id ]);
        }

        if( $kw ){
            $where_name = [ 'LIKE','name','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $where_mobile = [ 'LIKE','mobile','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $where_email = [ 'LIKE','email','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $query = $query->andWhere( [ "OR",$where_name,$where_mobile ,$where_email ] );
        }

        $pages = UtilHelper::ipagination([
            'total_count' => $query->count(),
            'page_size' => $this->page_size,
            'page' => $p,
            'display' => 10
        ]);

        $list = $query->orderBy([ 'id' => SORT_DESC ])
            ->offset($offset)
            ->limit($this->page_size)
            ->asArray()->all();

        $data = [];
        $department_map = Department::find()->select(['id', 'name'])
            ->where([
                'status' => ConstantService::$default_status_true,
                'merchant_id' => $this->getMerchantId(),
                'app_id' => $this->getAppId(),
            ])->indexBy("id")->asArray()->all();
        if( $list ){
            foreach ($list as $_item ){
                $tmp_depart_info = $department_map[ $_item['department_id'] ]??[];
                $tmp_data = [
                    "id" => $_item['id'],
                    "name" => DataHelper::encode( $_item['name'] ),
                    "nickname" => DataHelper::encode( $_item['nickname'] ),
                    "email" => DataHelper::encode( $_item['email'] ),
                    "mobile" => DataHelper::encode( $_item['mobile'] ),
                    "listen_nums" => DataHelper::encode( $_item['listen_nums'] ),
                    "depart_info" => $tmp_depart_info,
                    "status" => DataHelper::encode( $_item['status'] ),
                    "status_desc" =>  ConstantService::$common_status_mapping2[$_item['status']],
                    "created_time" => DataHelper::encode( $_item['created_time'] ),
                ];
                $data[] = $tmp_data;
            }
        }


        return $this->render('index', [
            "list" => $data,
            "pages" => $pages,
            'department_list' => $department_map,
            'sc' => [
                'kw' => $kw,
                'department_id' => $department_id,
            ]
        ]);
    }

    /**
     * 编辑或添加界面.
     * @return string|\yii\web\Response
     */
    public function actionEdit()
    {
        $staff_id = intval($this->get('staff_id', 0));

        if ($staff_id > 0) {
            $staff = Staff::find()
                ->where([
                    'id' => $staff_id,
                    'merchant_id' => $this->getMerchantId(),
                ])
                ->andWhere(['like', 'app_ids', '%,' . $this->getAppId() . ',%', false])
                ->one();
        } else {
            $staff = new Staff();
        }

        if ($staff_id && !$staff) {
            // 返回回去.
            return $this->responseFail('您暂无权限操作');
        }

        $departments = Department::find()
            ->where([
                'status' => ConstantService::$default_status_true,
                'merchant_id' => $this->getMerchantId(),
                'app_id' => $this->getAppId(),
            ])
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        // 所有角色.
        $roles = Role::find()
            ->where([
                'status' => ConstantService::$default_status_true,
                'merchant_id' => $this->getMerchantId(),
                'app_id' => $this->getAppId(),
            ])
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        $role_ids = StaffRole::find()
            ->where([
                'status' => ConstantService::$default_status_true,
                'staff_id' => $staff['id'],
                'app_id' => $this->getAppId(),
            ])
            ->select(['role_id'])
            ->column();

        return $this->render('edit', [
            'staff' => $staff,
            'departments' => $departments,
            'roles' => $roles,
            'role_ids' => $role_ids,
        ]);
    }

    /**
     * 信息保存.
     */
    public function actionSave()
    {
        $data = $this->post(null);

        $request_r = [
            'mobile',
            'email',
            'nickname',
            'name',
            'listen_nums',
            'department_id',
            'avatar',
            'password',
            'confirm_password',
            'id'
        ];

        if (count(array_intersect(array_keys($data), $request_r)) != count($request_r)) {
            return $this->renderErrJSON('参数丢失');
        }

        // 开始判断.
        if (!ValidateHelper::validLength($data['nickname'], 1, 255)) {
            return $this->renderErrJSON('请输入正确长度的昵称');
        }

        // 开始判断.
        if (!ValidateHelper::validMobile($data['mobile'])) {
            return $this->renderErrJSON('请输入正确的手机号');
        }

        if (!ValidateHelper::validEmail($data['email'])) {
            return $this->renderErrJSON('请输入正确的邮箱号码');
        }

        if (!ValidateHelper::validLength($data['name'], 1, 255)) {
            return $this->renderErrJSON('请输入正确的姓名/商户名');
        }

        $default = \Yii::$app->params['default_chat_config']['listen_num'];
        if (!is_numeric($data['listen_nums']) || !ValidateHelper::validRange($data['listen_nums'], $default['min'],
                $default['max'])) {
            return $this->renderErrJSON('请输入正确的接听数,最小为' . $default['min'] . ',最大为' . $default['max']);
        }

        $departments = Department::find()
            ->where([
                'status' => ConstantService::$default_status_true,
                'merchant_id' => $this->getMerchantId(),
                'app_id' => $this->getAppId(),
            ])
            ->select(['id'])
            ->column();

        if ($data['department_id'] && !in_array($data['department_id'], $departments)) {
            return $this->renderErrJSON('请选择正确的部门');
        }

        if ($data['password'] && $data['password'] != $data['confirm_password']) {
            return $this->renderErrJSON('两次输入的密码不一致,请重新输入');
        }

        $role_ids = Role::find()
            ->where([
                'status' => ConstantService::$default_status_true,
                'merchant_id' => $this->getMerchantId(),
                'app_id' => $this->getAppId(),
            ])
            ->select(['id'])
            ->column();

        if (array_key_exists('role_ids', $data) && array_diff($data['role_ids'], $role_ids)) {
            return $this->renderErrJSON('请选择正确的角色');
        }

        // 检查密码强度.
        if ($data['password'] && !CommonService::checkPassLevel($data['password'])) {
            return $this->renderErrJSON(CommonService::getLastErrorMsg());
        }

        if (!$data['id'] && !$data['password']) {
            return $this->renderErrJSON('新增帐号时请输入密码');
        }

        $other_staff = Staff::find()
            ->where([
                'mobile' => $data['mobile']
            ])
            ->andWhere(['!=', 'id', $data['id']])
            ->one();

        // 这里还需要检查一下mobile.
        if ($other_staff) {
            return $this->renderErrJSON('您更换的手机号已经被别人所使用了');
        }

        $other_staff = Staff::find()
            ->where([
                'email' => $data['email']
            ])
            ->andWhere(['!=', 'id', $data['id']])
            ->one();

        // 这里还需要检查一下mobile.
        if ($other_staff) {
            return $this->renderErrJSON('您添加的邮箱已经被其他人所使用了');
        }

        if ($data['id'] > 0) {
            $staff = Staff::find()
                ->where([
                    'id' => $data['id'],
                    'merchant_id' => $this->getMerchantId(),
                ])
                ->andWhere(['like', 'app_ids', '%,' . $this->getAppId() . ',%', false])
                ->one();
        } else {
            $staff = new Staff();
        }

        if ($data['id'] > 0 && !$staff['id']) {
            return $this->renderErrJSON('非法的员工');
        }

        if (!$staff && Staff::findOne(['email' => $data['email']])) {
            return $this->renderErrJSON('该邮箱已经被使用了,请稍后重新添加');
        }

        if (!$data['id']) {
            $data['sn'] = CommonService::genUniqueName();
            $data['merchant_id'] = $this->getMerchantId();
            $data['status'] = ConstantService::$default_status_true;
            $data['salt'] = CommonService::genUniqueName();
            $data['avatar'] = ConstantService::$default_avatar;
            // 设置应用ID.
            $data['app_ids'] = ',' . $this->getAppId() . ',';
            $staff->setAttributes($data);
        }

        if ($data['password']) {
            $data['password'] = $this->genPassword($this->getMerchantId(), $data['password'], $staff['salt']);
            $data['is_online'] = ConstantService::$default_status_false;
        } else {
            unset($data['password']);
        }

        $staff->setAttributes($data, 0);

        if (!$staff->save(0)) {
            return $this->renderErrJSON('数据库保存失败,请联系管理员');
        }

        if (array_key_exists('role_ids', $data) && !RoleService::createRoleMapping($staff['id'], $this->getAppId(),
                $data['role_ids'])) {
            return $this->renderErrJSON(RoleService::getLastErrorMsg());
        }

        if ($staff['id'] == $this->getStaffId() && isset($data['password'])) {
            $cookie = \Yii::$app->params['cookies']['staff'];
            // 删除cookie
            $this->removeCookie($cookie['name'], $cookie['domain']);
            return $this->renderJSON(['url' => GlobalUrlService::buildUcUrl('/user/login')], '操作成功');
        }

        return $this->renderJSON([], '操作成功');
    }


    public function actionOps(){
        if(!$this->isAjax()){
            return $this->renderErrJSON( ConstantService::$default_sys_err );
        }

        $id = $this->post("id",0);
        $act = $this->post("act","");
        if( !$id || !$act ){
            return $this->renderErrJSON( ConstantService::$default_sys_err );
        }

        $staff_info = Staff::find()->where(["id" => $id,"merchant_id" => $this->getMerchantId() ])->one();
        if( !$staff_info ){
            return $this->renderErrJSON( ConstantService::$default_sys_err);
        }
        switch($act) {
            case "disable":
                $staff_info->status = ConstantService::$default_status_false;
                $staff_info->save( 0 );
                break;
            case "recover":
                $staff_info->status = ConstantService::$default_status_true;
                $staff_info->save( 0 );
                break;
        }
        return $this->renderJSON([],"设置账号状态成功~~");
    }
}

<?php

namespace www\modules\merchant\controllers\common;

use common\components\BaseWebController;
use common\models\merchant\Merchant;
use common\models\merchant\Staff;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use www\modules\merchant\service\MenuService;
use www\modules\merchant\service\RoleService;
use Yii;
use yii\base\Action;
use yii\web\Response;

class BaseController extends BaseWebController {

    public $merchant_info;
    public $staff;

    public $merchant_cookie_name = 'chat_merchant_cookie';

    protected  $allowAllAction = [
        'merchant/user/login',
        'merchant/user/sign-in',
        'merchant/user/register',
    ];

    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $view = Yii::$app->view;
        $view->params['id'] = $id;
        $this->layout = "main";
    }

    /**
     * 检查权限.
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if(in_array($action->getUniqueId(), $this->allowAllAction)) {
            return true;
        }

        if(!$this->checkLoginStatus()) {
            // 设置跳转.
            $this->redirect(GlobalUrlService::buildMerchantUrl('/user/login',[
                'redirect_uri'  =>  GlobalUrlService::buildWwwUrl('/' . $action->getUniqueId())
            ]));
            return false;
        }

        $urls = RoleService::getRoleUrlsByStaffId($this->staff['id'], $this->staff['is_root']);

        if(!$this->staff['is_root'] && !in_array($action->getUniqueId(), $urls)) {
            exit('这里没有权限.');
            return false;
        }

        $menus = MenuService::getAllMenu($urls, $this->staff['is_root']);

        Yii::$app->view->params['merchant'] = $this->merchant_info;
        Yii::$app->view->params['staff']    = $this->staff;
        Yii::$app->view->params['menus']    = $menus;
        return true;
    }

    /**
     * 开始商户登录权限.
     */
    protected function checkLoginStatus()
    {
        $auth_cookie = $this->getCookie($this->merchant_cookie_name,'');
        // 这里稍微注意下.
        @list($staff_id, $verify_token) = explode('#', $auth_cookie);

        // 一个都没有.那就验证失败.
        if(!$staff_id || !$verify_token) {
            return false;
        }

        $staff = Staff::findOne(['id'=>$staff_id, 'status'=>ConstantService::$default_status_true]);

        if(!$staff || !$this->checkToken($verify_token, $staff)) {
            return false;
        }

        // 保存信息.
        $this->staff = $staff->toArray();

        $merchant = Merchant::findOne(['id'=>$staff['merchant_id'],'status'=>ConstantService::$default_status_true]);
        if(!$merchant) {
            return false;
        }

        $this->merchant_info = $merchant->toArray();

        return true;
    }

    /**
     * 创建登录的状态.
     * @param $staff
     */
    public function createLoginStatus($staff)
    {
        $token = $staff['id'] . '#' . $this->genToken($staff['merchant_id'], $staff['salt'], $staff['password']);
        // 指定区域.
        $this->setCookie($this->merchant_cookie_name, $token,0,'','/merchant');
    }

    /**
     * 验证token.
     * @param $token
     * @param $staff
     * @return bool
     */
    protected function checkToken($token, $staff)
    {
        return $token == $this->genToken($staff['merchant_id'], $staff['salt'], $staff['password']);
    }

    /**
     * 生成登录令牌.
     * @param $merchant_id
     * @param $staff_salt
     * @param $password
     * @return string
     */
    protected function genToken($merchant_id, $staff_salt, $password)
    {
        return md5($staff_salt . md5($merchant_id . $password));
    }

    /**
     * 生成密码.
     * @param $merchant_id
     * @param $password
     * @param $salt
     * @return string
     */
    protected function genPassword($merchant_id,$password,$salt)
    {
        return md5($merchant_id . '-' . $password  . '-' . $salt);
    }

    /**
     * 获取商户ID.
     * @return int
     */
    public function getMerchantId()
    {
        return $this->merchant_info ? $this->merchant_info['id'] : 0;
    }

    /**
     * 获取员工ID.
     * @return int
     */
    public function getStaffId()
    {
        return $this->staff ? $this->staff['id'] : 0;
    }

    /**
     * 渲染分页的界面.
     * @param array $data
     * @param string $msg
     * @param int $count
     * @return \yii\console\Response|Response
     */
    public function renderPageJSON($data = [], $msg = '', $count = 0)
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data   = [
            'msg'    => $msg,
            'code'   => 0,
            'data'   => $data,
            'count'  => $count,
            'req_id' => $this->geneReqId()
        ];

        return $response;
    }
}
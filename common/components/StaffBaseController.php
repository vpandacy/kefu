<?php
namespace common\components;

use common\models\uc\Merchant;
use common\models\uc\Staff;
use common\services\uc\RoleService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\AppService;
use yii\web\Response;
use yii\base\Action;
use Yii;

class StaffBaseController extends BaseWebController
{
    /**
     * 商户信息.
     * @var array
     */
    public $merchant_info = null;

    /**
     * @var Staff null
     */
    public $current_user = null;

    /**
     * 应用id.
     * @var int
     */
    protected $app_id = 0;

    public $privilege_urls = [];


    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config);
        $view = Yii::$app->view;
        $view->params['id'] = $id;
    }

    /**
     * 设置错误的响应.
     * @param string $msg
     * @return Response
     */
    public function responseFail($msg = '非法请求')
    {
        return $this->redirect(GlobalUrlService::buildUcUrl('/error/error',[
            'msg'   =>  $msg
        ]));
    }

    /**
     * 开始商户登录权限.
     */
    protected function checkLoginStatus()
    {
        $cookie = Yii::$app->params['cookies']['staff'];
        $auth_cookie = $this->getCookie($cookie['name'],'');
        // 这里稍微注意下.
        @list($staff_id, $verify_token) = explode('#', $auth_cookie);

        // 一个都没有.那就验证失败.
        if(!$staff_id || !$verify_token) {
            return false;
        }

        $staff = Staff::findOne([ 'id' => $staff_id,'status' => ConstantService::$default_status_true]);
        if(!$staff || !$this->checkToken($verify_token, $staff)) {
            return false;
        }
        // 保存信息.
        $this->current_user = $staff;
        return true;
    }

    /**
     * 创建登录的状态.
     * @param $staff
     * @return string
     */
    public function createLoginStatus($staff)
    {
        $token = $this->genToken($staff['merchant_id'], $staff['salt'], $staff['password']);
        $cookie = Yii::$app->params['cookies']['staff'];
        $expired_time = strtotime(date("Y-m-d 23:59:59")) - time() + 3600;
        // 指定区域.
        $this->setCookie($cookie['name'], $staff['id'] . '#' . $token,$expired_time, $cookie['domain']);
        return $token;
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
        return $this->current_user ? $this->current_user['id'] : 0;
    }

    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * 设置应用ID.
     * @param int $app_id
     */
    public function setAppId($app_id)
    {
        $this->app_id = $app_id;
    }

    //这个方法未来可以在业务中判断数据权限
    public function checkPrivilege($url, $ignore_admin = false)
    {

        //如果当前用户是管理员的话 就无须验证权限
        if (!$ignore_admin && $this->isRoot() ) {
            $this->getRolePrivilege();
            return true;
        }

        if ($this->ignore_url && preg_match("#" . implode("|", $this->ignore_url) . "#", $url)) {
            return true;
        }

        if (substr($url, 0, 1) == "/") {
            $url = substr($url, 1);
        }

        return in_array($url, $this->getRolePrivilege());
    }

    /**
     * Author: Vincent
     * @param string $type
     * @param bool $ignore_admin
     * @return bool
     * 判断当前人是否有当前url的个人my
     * |下属sub|全部的权限all
     * 当然type是其他的也可以的
     */
    public function checkDataPrivilege( $type = 'all',$ignore_admin = false){
        $url = \Yii::$app->request->pathInfo;
        $check_url = "{$url}_{$type}";
        return $this->checkPrivilege( $check_url,$ignore_admin );
    }

    //获取指定员工的权限
    public function getRolePrivilege($staff_id = 0)
    {
        if ( !$staff_id && $this->current_user) {
            $staff_id = $this->current_user['id'];
        }

        if (empty($this->privilege_urls)) {
            $urls = RoleService::getRoleUrlsByStaffId($this->getAppId(),$staff_id, $this->isRoot() );
            $this->privilege_urls = array_unique($urls);
        }

        return $this->privilege_urls;
    }

    protected function isRoot(){
        return $this->current_user['is_root'];
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
            'code'   => ConstantService::$response_code_success,
            'data'   => $data,
            'count'  => $count,
            'req_id' => $this->geneReqId()
        ];

        return $response;
    }

    public function getFrom(){
        if( $this->isFromElectron() ){
            return ConstantService::$CS_APP;
        }
        return trim( $this->get("from",$this->post("from","") ) );
    }

    private function isFromElectron(){
        $ua = Yii::$app->request->getUserAgent();
        $idx = stripos( $ua ,"Electron");
        return ( $idx === false )? false:true;
    }
}
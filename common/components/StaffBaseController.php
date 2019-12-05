<?php
namespace common\components;

use common\models\uc\Merchant;
use common\models\uc\Staff;
use common\services\uc\RoleService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use uc\service\AppService;
use uc\service\UcUrlService;
use yii\web\Response;
use yii\base\Action;
use Yii;

class StaffBaseController extends BaseWebController
{
    /**
     * 商户信息.
     * @var array
     */
    public $merchant_info;

    /**
     * 员工信息.
     * @var Staff
     */
    public $staff;

    /**
     * 应用id.
     * @var int
     */
    private $app_id = 0;

    public $privilege_urls = [];

    // 可以不用登录的地方.
    protected  $allowAllAction = [
        'user/login',
        'user/sign-in',
        'user/register',
        'user/logout',
    ];

    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config);
        $view = Yii::$app->view;
        $view->params['id'] = $id;
    }

    /**
     * 检查权限.
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $app_name = $this->get('app_name','');

        $this->app_id = AppService::getAppId($app_name);

        Yii::$app->view->params['app_name'] = $app_name;

        if(in_array($action->getUniqueId(), $this->allowAllAction)) {
            return true;
        }

        if(!$this->checkLoginStatus()) {
            if(Yii::$app->request->isAjax) {
                $this->renderJSON([],'您还没有登录,请登录', -401);
                return false;
            }

            // 设置跳转.
            $this->redirect(UcUrlService::buildUcUrl('/user/login', $this->getAppId(),[
                'redirect_uri'  =>  UcUrlService::buildUcUrl('/' . $action->getUniqueId(), $this->getAppId())
            ]));

            return false;
        }

        $urls = RoleService::getRoleUrlsByStaffId($this->staff['id'], $this->staff['is_root']);
        if(!$this->staff['is_root'] && !in_array($action->getUniqueId(), $urls)) {
            $this->responseFail('暂无权限操作');
            return false;
        }

        // 商户信息.
        Yii::$app->view->params['merchant'] = $this->merchant_info;
        // 员工信息.
        Yii::$app->view->params['staff']    = $this->staff;

        $this->privilege_urls = $urls;
        return true;
    }

    /**
     * 设置错误的响应.
     * @param string $msg
     * @return Response
     */
    public function responseFail($msg = '非法请求')
    {
        return $this->redirect(GlobalUrlService::buildWwwUrl('/error/error',[
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

        $staff = Staff::findOne(['id'=>$staff_id, 'status'=>ConstantService::$default_status_true]);

        if(!$staff || !$this->checkToken($verify_token, $staff)) {
            return false;
        }

        // 保存信息.
        $this->staff = $staff;

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
        $cookie = Yii::$app->params['cookies']['staff'];
        // 指定区域.
        $this->setCookie($cookie['name'], $token,0, $cookie['domain']);
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

    public function getAppId()
    {
        return $this->app_id;
    }
}
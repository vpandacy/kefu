<?php
namespace www\controllers;

use common\components\helper\ValidateHelper;
use common\models\merchant\GroupChat;
use common\models\merchant\LeaveMessage;
use common\models\uc\Merchant;
use common\models\uc\MerchantSetting;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\monitor\WSCenterService;
use common\services\uc\MerchantService;
use www\assets\MerchantAsset;
use www\controllers\common\BaseController;

/**
 * 游客端
 * Class CodeController
 * @package www\controllers
 */
class CodeController extends BaseController
{
    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config);
        $this->layout = 'main';
    }

    public function actionIndex()
    {
        /**
         * 这里就能获取到对应的商户和客服了. 先不获取.一直传就可以了.
         */
        $uuid = $this->getGuestUUID();
        $msn = $this->get('msn','');
        $code = $this->get('code','');

        $this->layout = false;
        $is_mobile = CommonService::isMobile();
        $params = [
            'msn' => $msn,
            'uuid' => $uuid
        ];
        if( $code ){
            $params['code'] = $code;
        }
        $url = GlobalUrlService::buildKFMSNUrl( $is_mobile ? '/code/mobile' : '/code/chat', $params );
        header('Content-type: text/javascript');
        return $this->render('index.js',[
            'url'   =>  $url,
            'uuid' => $uuid,
            'is_mobile' =>  $is_mobile
        ]);
    }

    /**
     * 这里是直接访问.直接访问时生成uuid.
     * @return string
     */
    public function actionChat()
    {
        $msn = $this->get('msn','');
        $code = $this->get('code',0);
        $uuid = $this->get("uuid",$this->getGuestUUID() );
        if(!$msn) {
            return '<script>alert("您引入的非法客服软件-1~~");</script>';
        }

        // 这里最好加入到缓存中去.不然到时候会比较麻烦.
        $merchant_info = MerchantService::getInfoBySn( $msn );
        if( !$merchant_info ) {
            return '<script>alert("您引入的非法客服软件-2~~");</script>';
        }

        $config = MerchantService::getConfig( $merchant_info['id'] );
        $style = MerchantService::getStyleConfig( $code, $merchant_info['id'] );

        if(!$style) {
            return '<script>alert("您引入的非法客服软件-3~~");</script>';
        }

        $params = [
            "msn" => $msn,
            "code" => $code,
            "uuid" => $uuid
        ];

        $tab_url = GlobalUrlService::buildKFMSNUrl( '/code/online',$params );

        $data = [
            "merchant_info" => $merchant_info,
            "js_params"     => [
                'uuid'              => $uuid,
                "tab_url"           => $tab_url,
                "ws"                => WSCenterService::getGuestWSByRoute( $merchant_info['id'] ),
                "code"              => $code,
                "msn"               => $msn,
                "auto_disconnect"   => $config['auto_disconnect'] ?? 0,
                "greetings"         => $config['greetings'] ?? '您好,欢迎使用好商汇客服系统',
                "style"             => $style
            ]
        ];

        return $this->render('chat',$data);
    }

    public function actionOnline()
    {
        $msn = $this->get('msn','');
        $uuid = $this->get("uuid",$this->getGuestUUID() );
        $code = $this->get('code',0);
        if(!$msn) {
            return '<script>alert("您引入的非法客服软件")</script>';
        }

        // 这里最好加入到缓存中去.不然到时候会比较麻烦.
        $merchant = MerchantService::getInfoBySn($msn);

        if(!$merchant) {
            return '<script>alert("您引入的非法客服软件")</script>';
        }

        $style = MerchantService::getStyleConfig( $code, $merchant['id'] );

        if(!$style) {
            return '<script>alert("您引入的非法客服软件-3~~");</script>';
        }

        $setting = MerchantSetting::findOne(['merchant_id'=>$merchant['id']]);

        return $this->render('online',[
            'merchant_info'  =>  $merchant,
            'setting'   =>  $setting,
            "js_params" => [
                'uuid'              => $uuid,
                "ws"                => WSCenterService::getGuestWSByRoute( $msn ),
                "code"              => $code,
                "msn"               => $msn,
                "auto_disconnect"   => $setting['auto_disconnect'] ?? 0,
                "greetings"         => $setting['greetings'] ?? '您好,欢迎使用好商汇客服系统',
                "style"             => $style
            ]
        ]);
    }

    /**
     * 这里是手机端界面.
     */
    public function actionMobile()
    {
        $msn = $this->get('msn','');
        $code = $this->get('code','');
        $uuid = $this->get("uuid",$this->getGuestUUID() );

        if(!$msn) {
            return '<script>alert("您引入的非法客服软件")</script>';
        }

        // 这里最好加入到缓存中去.不然到时候会比较麻烦.
        $merchant = Merchant::findOne(['sn'=>$msn,'status'=>ConstantService::$default_status_true]);

        if(!$merchant) {
            return '<script>alert("您引入的非法客服软件")</script>';
        }

        $style = MerchantService::getStyleConfig( $code, $merchant['id'] );

        if(!$style) {
            return '<script>alert("您引入的非法客服软件-3~~");</script>';
        }

        $setting = MerchantSetting::findOne(['merchant_id'=>$merchant['id']]);
        $this->layout = 'mobile';
        return $this->render('mobile', [
            'merchant_info'  =>  $merchant,
            'setting'   =>  $setting,
            "js_params" => [
                'uuid'              => $uuid,
                "ws"                => WSCenterService::getGuestWSByRoute( $msn ),
                "code"              => $code,
                "msn"               => $msn,
                "auto_disconnect"   => $setting['auto_disconnect'] ?? 0,
                "greetings"         => $setting['greetings'] ?? '您好,欢迎使用好商汇客服系统',
                "style"             => $style
            ]
        ]);
    }

    /**
     * 保存留言信息.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionLeave()
    {
        $uuid = $this->getGuestUUID();

        $code = $this->post('code','');
        $msn  = $this->post('msn','');
        $mobile = $this->post('mobile','');
        $wechat = $this->post('wechat','');
        $name = $this->post('name','');
        $message = $this->post('message','');

        if(!$msn) {
            return $this->renderErrJSON('找不到该客服信息');
        }

        if($wechat && !ValidateHelper::validLength($wechat,1,255)) {
            return $this->renderErrJSON('请输入正确格式的微信号，长度不能超过255');
        }

        if($name && !ValidateHelper::validLength($name,1,255)) {
            return $this->renderErrJSON('请输入正确格式的姓名，长度不能超过255');
        }

        if(!ValidateHelper::validMobile($mobile)) {
            return $this->renderErrJSON('请输入正确的手机号');
        }

        if(ValidateHelper::validIsEmpty($message) || !ValidateHelper::validLength($message,1,255)) {
            return $this->renderErrJSON('请输入正确格式的留言信息，长度不能超过255');
        }

        // 这里最好加入到缓存中去.不然到时候会比较麻烦.
        $merchant = MerchantService::getInfoBySn( $msn );
        if( !$merchant ) {
            return $this->renderErrJSON('找不到该商户信息');
        }

        $group_chat_id = 0;
        if($code) {
            $group_chat_id = GroupChat::find()->where(['sn'=>$code,'merchant_id'=>$merchant['id']])
                ->select(['id'])
                ->scalar();

            if(!$group_chat_id) {
                return $this->renderErrJSON('没有找到该风格信息');
            }
        }

        // 开始入库.
        $leave_message = new LeaveMessage();

        $leave_message->setAttributes([
            'visitor_id'    =>  $uuid,  // 这里先暂定.
            'merchant_id'   =>  $merchant['id'],
            'group_chat_id' =>  $group_chat_id,
            'mobile'        =>  $mobile,
            'wechat'        =>  $wechat,
            'name'          =>  $name,
            'message'       =>  $message
        ],0);

        if(!$leave_message->save(0)) {
            return $this->renderErrJSON('数据保存失败，请联系客服');
        }

        return $this->renderJSON( '感谢您的留言' );
    }
}

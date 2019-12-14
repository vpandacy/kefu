<?php
namespace www\controllers;

use common\models\uc\Merchant;
use common\models\uc\MerchantSetting;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use common\services\monitor\WSCenterService;
use common\services\uc\MerchantService;
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
        $url = GlobalUrlService::buildKFMSNUrl( $is_mobile ?'/code/mobile':'/code/chat',$params );
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
        $params = [
            "msn" => $msn,
            "code" => $code,
            "uuid" => $uuid
        ];
        $tab_url = GlobalUrlService::buildKFMSNUrl( '/code/online',$params );
        $data = [
            "merchant_info" => $merchant_info,
            "setting" => $config,
            "js_params" => [
                'uuid' => $uuid,
                "tab_url" => $tab_url,
                "ws" => WSCenterService::getGuestWSByRoute( $msn ),
                "code" => $code,
                "msn" => $msn
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
        $merchant = Merchant::findOne(['sn'=>$msn,'status'=>ConstantService::$default_status_true]);

        if(!$merchant) {
            return '<script>alert("您引入的非法客服软件")</script>';
        }

        $setting = MerchantSetting::findOne(['merchant_id'=>$merchant['id']]);

        return $this->render('online',[
            'merchant'  =>  $merchant,
            'setting'   =>  $setting,
            "js_params" => [
                'uuid' => $uuid,
                "ws" => WSCenterService::getGuestWSByRoute( $msn ),
                "code" => $code,
                "msn" => $msn
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

        $setting = MerchantSetting::findOne(['merchant_id'=>$merchant['id']]);
        $this->layout = 'mobile';
        return $this->render('mobile', [
            'merchant'  =>  $merchant,
            'setting'   =>  $setting,
            'code'      =>  $code,
            "js_params" => [
                'uuid' => $uuid,
                "ws" => WSCenterService::getGuestWSByRoute( $msn ),
                "code" => $code,
                "msn" => $msn
            ]
        ]);
    }
}

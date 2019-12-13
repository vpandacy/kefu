<?php
namespace www\controllers;

use common\models\uc\Merchant;
use common\models\uc\MerchantSetting;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
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
        // 这里就能获取到对应的商户和客服了. 先不获取.一直传就可以了.
        $msn = $this->get('msn','');
        $code = $this->get('code','');
        $this->layout = false;

        $is_mobile = CommonService::isMobile();

        $base_url = $is_mobile ? '/' . $msn . '/code/mobile' : '/'. $msn . '/code/chat';

        $url = $code
            ? GlobalUrlService::buildKFUrl($base_url,['code'=>$code])
            : GlobalUrlService::buildKFUrl($base_url);

        return $this->render('index',[
            'url'   =>  $url,
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

        $uuid = $this->getGuestUUID();

        if(!$msn) {
            return '<script>alert("您引入的非法客服软件")</script>';
        }

        // 这里最好加入到缓存中去.不然到时候会比较麻烦.
        $merchant = Merchant::findOne(['sn'=>$msn,'status'=>ConstantService::$default_status_true]);

        if(!$merchant) {
            return '<script>alert("您引入的非法客服软件")</script>';
        }

        $setting = MerchantSetting::findOne(['merchant_id'=>$merchant['id']]);

        return $this->render('chat',[
            'merchant'  =>  $merchant,
            'setting'   =>  $setting,
            'uuid'      =>  $uuid,
            'host'      =>  '192.168.117.122:8282', // 写死成自己的.  好调试代码.
            'code'      =>  $this->get('code'),
        ]);
    }

    public function actionOnline()
    {
        $msn = $this->get('msn','');

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
            'setting'   =>  $setting
        ]);
    }

    /**
     * 这里是手机端界面.
     */
    public function actionMobile()
    {
        $msn = $this->get('msn','');

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
            'host'      =>  '192.168.117.122:8282', // 写死成自己的.  好调试代码.
        ]);
    }
}

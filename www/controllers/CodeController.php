<?php
namespace www\controllers;

use common\models\uc\Merchant;
use common\models\uc\MerchantSetting;
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
        parent::__construct($id, $module, $config = []);
        $this->layout = 'main';
    }

    public function actionIndex()
    {
        // 这里就能获取到对应的商户和客服了. 先不获取.一直传就可以了.
        $msn = $this->get('msn','');
        $code = $this->get('code','');
        $this->layout = false;
        $url = $code
            ? GlobalUrlService::buildWwwUrl('/'. $msn . '/code/chat',['code'=>$code])
            : GlobalUrlService::buildWwwUrl('/' . $msn . '/code/chat');

        return $this->render('index',[
            'url'   =>  $url
        ]);
    }

    public function actionChat()
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

        return $this->render('chat_mini',[
            'merchant'  =>  $merchant,
            'setting'   =>  $setting
        ]);
    }

    public function actionOnline()
    {
        return $this->render("online");
    }
}

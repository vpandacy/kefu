<?php
namespace www\assets;

use common\services\GlobalUrlService;
use yii\web\AssetBundle;


class MerchantAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];

    public $js = [];

    public function registerAssetFiles($view){
        $this->css = [
            GlobalUrlService::buildStaticUrl('/font-awesome/v4.7/css/font-awesome.min.css'),
            GlobalUrlService::buildStaticUrl('/layui/v2.5/css/layui.css'),

            // 这里应该也是uc的css.有一部分.
            GlobalUrlService::buildUcStaticUrl('/css/component/animate.css'),
            GlobalUrlService::buildUcStaticUrl('/css/component/scrollbar.min.css'),
            GlobalUrlService::buildUcStaticUrl('/css/iconfont/iconfont.css'),
            GlobalUrlService::buildUcStaticUrl('/css/common_default.css'),
            GlobalUrlService::buildUcStaticUrl('/css/merchant/merchant.css'),
            GlobalUrlService::buildUcStaticUrl('/css/merchant/merchantfrom.css'),
            // 这下面写自己的css.
        ];

        $this->js = [
            GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery-3.2.1.min.js'),
            GlobalUrlService::buildStaticUrl('/layui/v2.5/layui.all.js'),
            GlobalUrlService::buildStaticUrl('/Ie/html5Shiv.min.js'),
            GlobalUrlService::buildStaticUrl('/Ie/respond.js'),
            // Uc的js.
            GlobalUrlService::buildUcStaticUrl('/js/component/user/center.js'),
            GlobalUrlService::buildUcStaticUrl('/js/component/domResize.js'),
            GlobalUrlService::buildUcStaticUrl('/js/common/core.js'),
            GlobalUrlService::buildUcStaticUrl('/js/component/url/manager.js'),
            // 这里是自己的js.
            GlobalUrlService::buildWwwStaticUrl('/js/merchant/common.js'),
        ];

        parent::registerAssetFiles($view);
    }
}

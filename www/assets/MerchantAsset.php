<?php

namespace www\assets;

use common\components\helper\StaticAssetsHelper;
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
            GlobalUrlService::buildWwwStaticUrl("/css/merchant/common_default.css"),
            GlobalUrlService::buildWwwStaticUrl("/css/merchant/scrollbar.min.css"),
            GlobalUrlService::buildStaticUrl("/font-awesome/v4.7/css/font-awesome.min.css"),
            GlobalUrlService::buildWwwStaticUrl("/css/merchant/iconfont/iconfont.css"),
            GlobalUrlService::buildStaticUrl("/layui/v2.5/css/layui.css"),
            GlobalUrlService::buildWwwStaticUrl("/css/merchant/merchant.css"),
            GlobalUrlService::buildWwwStaticUrl("/css/merchant/merchantfrom.css"),
            GlobalUrlService::buildWwwStaticUrl("/css/merchant/animate.css")
        ];
        $this->js = [
            GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery-3.2.1.min.js'),
            GlobalUrlService::buildStaticUrl('/layui/v2.5/layui.all.js'),
            GlobalUrlService::buildWwwStaticUrl("/js/merchant/merchant.js"),
            GlobalUrlService::buildWwwStaticUrl("/js/merchant/domResize.js"),
            GlobalUrlService::buildStaticUrl('/Ie/html5Shiv.min.js'),
            GlobalUrlService::buildStaticUrl('/Ie/respond.js'),
        ];
        parent::registerAssetFiles($view);
    }
}

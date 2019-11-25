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
        ];
        $this->js = [
            GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery-3.2.1.min.js'),
            GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery.mCustomScrollbar.min.js'),
            GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery.mousewheel.min.js'),
            GlobalUrlService::buildWwwStaticUrl('/js/merchant/core.min.js'),
            GlobalUrlService::buildWwwStaticUrl('/js/merchant/common.js')
        ];
        parent::registerAssetFiles($view);
    }
}

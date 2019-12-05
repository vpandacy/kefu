<?php

namespace uc\assets;

use common\services\GlobalUrlService;
use yii\web\AssetBundle;


class UcAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];

    public $js = [];

    public function registerAssetFiles($view){
        $this->css = [
            GlobalUrlService::buildStaticUrl('/font-awesome/v4.7/css/font-awesome.min.css'),
            GlobalUrlService::buildStaticUrl('/layui/v2.5/css/layui.css'),
            GlobalUrlService::buildUcStaticUrl('/css/common_default.css'),
            GlobalUrlService::buildUcStaticUrl('/css/scrollbar.min.css'),
            GlobalUrlService::buildUcStaticUrl('/css/iconfont/iconfont.css'),
            GlobalUrlService::buildUcStaticUrl('/css/merchant.css'),
            GlobalUrlService::buildUcStaticUrl('/css/merchantfrom.css'),
            GlobalUrlService::buildUcStaticUrl('/css/animate.css')
        ];
        $this->js = [
            GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery-3.2.1.min.js'),
            GlobalUrlService::buildStaticUrl('/layui/v2.5/layui.all.js'),
            GlobalUrlService::buildUcStaticUrl('/js/merchant.js'),
            GlobalUrlService::buildUcStaticUrl('/js/domResize.js'),
            GlobalUrlService::buildStaticUrl('/Ie/html5Shiv.min.js'),
            GlobalUrlService::buildStaticUrl('/Ie/respond.js'),
            GlobalUrlService::buildUcStaticUrl('/js/common.js'),
            GlobalUrlService::buildUcStaticUrl('/js/core.js'),
        ];
        parent::registerAssetFiles($view);
    }
}

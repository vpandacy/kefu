<?php
namespace www\assets;

use common\services\GlobalUrlService;
use yii\web\AssetBundle;

class CsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [];

    public function registerAssetFiles($view)
    {
        $this->css = [
            GlobalUrlService::buildUcStaticUrl('/css/component/iconfont/iconfont.css'),
            GlobalUrlService::buildKFStaticUrl('/css/cs/exe.css'),
        ];

        $this->js = [
            GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery_kf-3.2.1.min.js'),
            GlobalUrlService::buildStaticUrl('/plugins/layer/layer.js'),
            GlobalUrlService::buildUcUrl('/js/common/core.js'),
            GlobalUrlService::buildWWWStaticUrl("/js/common/common.js"),
            GlobalUrlService::buildKFStaticUrl('/js/component/storage.js'),
            GlobalUrlService::buildKFStaticUrl("/js/cs/common.js"),
        ];

        parent::registerAssetFiles($view);
    }
}
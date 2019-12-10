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
            GlobalUrlService::buildDWStaticUrl('/css/cs/exe.css'),
        ];

        $this->js = [
            GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery-3.2.1.min.js'),
            GlobalUrlService::buildDWStaticUrl("/js/cs/exe.js"),
        ];

        parent::registerAssetFiles($view);
    }
}
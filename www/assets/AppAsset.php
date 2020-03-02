<?php
namespace www\assets;

use common\services\GlobalUrlService;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [];

    public function registerAssetFiles($view)
    {
        $this->css = [];

        $this->js = [
//            GlobalUrlService::buildStaticUrl("/socket/socket.io.js"),
        ];

        parent::registerAssetFiles($view);
    }
}
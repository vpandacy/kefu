<?php
namespace www\assets;

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

        $this->js = [];

        parent::registerAssetFiles($view);
    }
}
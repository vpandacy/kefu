<?php

namespace uc\assets;

use common\services\GlobalUrlService;
use uc\service\UcUrlService;
use yii\web\AssetBundle;
use Yii;

/**
 * 由于视图在控制器后加载.而资源管理是在控制器后在进行渲染.所以能通过视图来得到对应的app_id.
 * Class UcAsset
 * @package uc\assets
 */
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
            UcUrlService::buildUcStaticUrl('/css/common_default.css'),
            UcUrlService::buildUcStaticUrl('/css/scrollbar.min.css'),
            UcUrlService::buildUcStaticUrl('/css/iconfont/iconfont.css'),
            UcUrlService::buildUcStaticUrl('/css/merchant.css'),
            UcUrlService::buildUcStaticUrl('/css/merchantfrom.css'),
            UcUrlService::buildUcStaticUrl('/css/animate.css')
        ];


        $this->js = [
            GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery-3.2.1.min.js'),
            GlobalUrlService::buildStaticUrl('/layui/v2.5/layui.all.js'),
            GlobalUrlService::buildStaticUrl('/Ie/html5Shiv.min.js'),
            GlobalUrlService::buildStaticUrl('/Ie/respond.js'),

            UcUrlService::buildUcStaticUrl('/js/component/domResize.js'),
            UcUrlService::buildUcStaticUrl('/js/component/url/manager.js'),
            UcUrlService::buildUcStaticUrl('/js/common/core.js'),
            UcUrlService::buildUcStaticUrl('/js/common.js'),
        ];
        parent::registerAssetFiles($view);
    }
}

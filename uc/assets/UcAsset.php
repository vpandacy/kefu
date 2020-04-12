<?php

namespace uc\assets;

use common\services\GlobalUrlService;
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

            GlobalUrlService::buildUcStaticUrl('/css/component/animate.css'),
            GlobalUrlService::buildUcStaticUrl('/css/component/scrollbar.min.css'),
            GlobalUrlService::buildUcStaticUrl('/css/component/iconfont/iconfont.css'),
            GlobalUrlService::buildUcStaticUrl('/css/common_default.css'),

            GlobalUrlService::buildUcStaticUrl('/css/common/core.css'),
        ];

        $this->js = [
            GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery_kf-3.2.1.min.js'),
            GlobalUrlService::buildStaticUrl('/layui/v2.5/layui.all.js'),
            GlobalUrlService::buildStaticUrl('/Ie/html5Shiv.min.js'),
            GlobalUrlService::buildStaticUrl('/Ie/respond.js'),
            // 全局的JS
            GlobalUrlService::buildUcStaticUrl('/js/common/core.js'),
            // uc自己的JS
            GlobalUrlService::buildUcStaticUrl('/js/common.js'),
        ];
        parent::registerAssetFiles($view);
    }
}

<?php

namespace admin\assets;

use common\components\helper\StaticAssetsHelper;
use common\services\GlobalUrlService;
use yii\web\AssetBundle;


class Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];

    public function registerAssetFiles($view){

        $release_version = StaticAssetsHelper::getReleaseVersion();
        $this->css = [
            GlobalUrlService::buildStaticUrl("/bootstrap/v3/css/bootstrap.min.css"),
            GlobalUrlService::buildStaticUrl("/font-awesome/css/font-awesome.min.css"),
            GlobalUrlService::buildStaticUrl("/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css"),
            GlobalUrlService::buildStaticUrl("/AdminLTE/bower_components/Ionicons/css/ionicons.min.css"),
            GlobalUrlService::buildStaticUrl("/AdminLTE/dist/css/AdminLTE.min.css"),
            GlobalUrlService::buildStaticUrl("/AdminLTE/dist/css/skins/_all-skins.min.css"),
            GlobalUrlService::buildKFAdminUrl("/css/common.css?version={$release_version}"),
        ];
        $this->js = [
            GlobalUrlService::buildStaticUrl("/plugins/jquery/jquery-3.2.1.min.js"),
            GlobalUrlService::buildStaticUrl("/bootstrap/v3/js/bootstrap.min.js"),
            GlobalUrlService::buildStaticUrl("/AdminLTE/bower_components/fastclick/lib/fastclick.js"),
            GlobalUrlService::buildStaticUrl("/AdminLTE/dist/js/adminlte.min.js"),
            GlobalUrlService::buildStaticUrl("/plugins/layer/layer.js"),
            GlobalUrlService::buildKFAdminUrl("/js/common.js?version={$release_version}"),
            "/js/common.js?version={$release_version}",

        ];
        parent::registerAssetFiles($view);
    }
}

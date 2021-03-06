<?php
namespace common\components\helper;

use common\services\GlobalUrlService;

/**
 * 资源组件管理.
 * Class StaticPluginHelper
 * @package common\components\helper
 */
class StaticPluginHelper
{
    private static $depend;

    // 设置依赖.
    public static function setDepend($depend = null)
    {
        self::$depend = $depend;
    }

    public static function getDepend()
    {
        return self::$depend;
    }

    /**
     * 引入七牛的插件.
     */
    public static function qiniuPlugin()
    {
        self::includeJsPlugins([
            '/plugins/qiniu/plupload/moxie.min.js',
            '/plugins/qiniu/plupload/plupload.full.min.js',
            '/plugins/qiniu/plupload/zh_CN.js',
            '/plugins/qiniu/qiniu.min.js',
            // 这个域名要待定.
            GlobalUrlService::buildUcStaticUrl('/js/component/qiniu/uploader.js'),
        ]);
    }

    /**
     * socket的兼容性js.兼容IE8,9.需要在页面创建并使用
     * <script>WEB_SOCKET_SWF_LOCATION = '<?=GlobalUrlService::buildStaticUrl('/socket/WebSocketMain.swf')?>'</script>
     */
    public static function socketPlugin()
    {
        self::includeJsPlugins([
            '/socket/swfobject.js',
            '/socket/web_socket.js',
        ]);
    }

    /**
     * 批量引入js资源.
     * @param array $plugins
     */
    public static function includeJsPlugins($plugins = [])
    {
        // 批量引用.
        foreach($plugins as $key => $plugin) {
            if(strpos($plugin,'http') !== 0) {
                $plugin = GlobalUrlService::buildStaticUrl($plugin);
            }
            StaticAssetsHelper::includeAppJsStatic($plugin, self::getDepend());
        }
    }

    /**
     * 批量引入css资源. 预留口子.
     * @param array $plugins
     */
    public static function includeCssPlugins($plugins = [])
    {
        // 批量引用.
        foreach($plugins as $key => $plugin) {
            if(strpos($plugin,'http') !== 0) {
                $plugin = GlobalUrlService::buildStaticUrl($plugin);
            }
            StaticAssetsHelper::includeAppCssStatic($plugin, self::getDepend());
        }
    }

    /**
     * 批量引入js资源.
     */
    public static function daterangepicker()
    {
        StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildStaticUrl("/plugins/daterangepicker/daterangepicker.min.css"),
            self::getDepend());
        StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl("/plugins/daterangepicker/moment.min.js"),
            self::getDepend());
        StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl("/plugins/daterangepicker/jquery.daterangepicker.min.js"),
            self::getDepend());
    }

    public static function select2()
    {
        StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildStaticUrl("/plugins/select2/select2.min.css"),
            self::getDepend());
        StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl("/plugins/select2/select2.pinyin.js"),
            self::getDepend());
        StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl("/plugins/select2/zh-CN.js"),
            self::getDepend());
        StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl("/plugins/select2/pinyin.core.js"),
            self::getDepend());
    }

    public static function umeditor()
    {
        StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildStaticUrl('/plugins/umeditor/themes/default/css/umeditor.css'),
            self::getDepend()
        );

        StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/plugins/umeditor/umeditor.config.js'),
            self::getDepend()
        );

        StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/plugins/umeditor/umeditor.js'),
            self::getDepend()
        );
    }

    public static function jqueryUIWidget(){
        StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildStaticUrl("/plugins/jquery_ui_components/jquery-ui.min.css"),
            self::getDepend());
        StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl("/plugins/jquery_ui_components/jquery-ui.min.js"),
            self::getDepend());
    }
}
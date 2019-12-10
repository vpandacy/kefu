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
}
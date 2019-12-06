<?php
namespace uc\service;

use common\services\BaseService;
use common\services\CommonService;
use yii\helpers\Url;
use Yii;

/**
 * 专为uc处理的生成器.
 * Class UcUrlService
 * @package uc\service
 */
class UcUrlService extends BaseService
{

    /**
     * 全局唯一的app_id.
     * @var int
     */
    protected static $app_id = 0;


    /**
     * 生成uc的浏览url.
     * @param string $uri 基础的ui.
     * @param array $params 参数.
     * @return string
     */
    public static function buildUcUrl($uri,  $params = [])
    {
        $app_id = self::getAppId();

        $app_str = isset(ConstantService::$app_mapping[$app_id]) ? ConstantService::$app_mapping[$app_id] : 'uc';

        $prefix = $app_str != 'uc' ? '/uc':'';

        $path = '';

        if( $uri ){
            $path   = Url::toRoute(array_merge([$uri], $params));
        }

        $domain_config = Yii::$app->params['domains'];

        $domain = $domain_config[$app_str];

        if (CommonService::is_SSL()) {
            $domain = str_replace('http://', 'https://', $domain);
        }

        return $domain . $prefix . $path;
    }

    /**
     * 引入uc的静态资源.
     * @param $uri
     * @param array $params
     * @return string
     */
    public static function buildUcStaticUrl($uri, $params = [])
    {
        return self::buildUcUrl($uri, $params);
    }

    /**
     * 设置app_id
     * @param int $app_id
     */
    public static function setAppId($app_id = 0)
    {
        self::$app_id = $app_id;
    }

    /**
     * 获取app_id. 是否应该从视图中获取?????还是一次性设置并永久获取.
     * @return int
     */
    public static function getAppId()
    {
        return self::$app_id;
    }
}
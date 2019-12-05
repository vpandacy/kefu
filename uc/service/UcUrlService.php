<?php
namespace uc\service;

use common\services\BaseService;
use common\services\CommonService;
use yii\helpers\Url;
use Yii;

class UcUrlService extends BaseService
{
    /**
     * 生成uc的浏览url.
     * @param string $uri 基础的ui.
     * @param int $app_id   应用ID.
     * @param array $params 参数.
     * @return string
     */
    public static function buildUcUrl($uri, $app_id = 0, $params = [])
    {
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
}
<?php
namespace uc\services;
use common\services\BaseService;
use common\components\helper\UtilHelper;
use \common\services\ConstantService;
use yii\helpers\Url;

class UCUrlService extends BaseService
{

    /**
     * Author: apanly
     * 页面空链接
     */
    public static function buildNullUrl()
    {
        return "javascript:void(0);";
    }

    public static function buildUCUrl($uri, $app_id = 0, $params = []){
        $app_str = ConstantService::$app_mapping[$app_id]??'uc';
        $prefix = $app_str != 'uc' ? '/uc':'';
        $path = "";
        if( $uri ){
            $path   = Url::toRoute(array_merge([$uri], $params));
        }
        $domain_config = \Yii::$app->params['domains'];
        $domain = $domain_config[$app_str];
        if (UtilHelper::is_SSL()) {
            $domain = str_replace("http://", "https://", $domain);
        }

        return $domain . $prefix . $path;
    }

    public static function buildUCStaticUrl( $uri, $app_id = 0, $params = [] ){
        return self::buildUCUrl($uri, $app_id,$params);
    }
}
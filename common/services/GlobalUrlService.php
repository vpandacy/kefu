<?php
namespace common\services;

use common\components\helper\StaticAssetsHelper;
use yii\helpers\Url;

class GlobalUrlService extends BaseService {
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

        $domain_config = \Yii::$app->params['domains'];

        $domain = $domain_config[$app_str];

        if (CommonService::is_SSL()) {
            $domain = str_replace('http://', 'https://', $domain);
        }

        return $domain . $prefix . $path;
    }

    /**
     * 获取原始的url.(不加uc的)
     * @param string $uri
     * @param array $params
     * @return string
     */
    public static function buildOriginUrl($uri, $params = [])
    {
        $app_id = self::getAppId();

        $app_str = isset(ConstantService::$app_mapping[$app_id]) ? ConstantService::$app_mapping[$app_id] : 'uc';

        $path = '';

        if( $uri ){
            $path   = Url::toRoute(array_merge([$uri], $params));
        }

        $domain_config = \Yii::$app->params['domains'];

        $domain = $domain_config[$app_str];

        if (CommonService::is_SSL()) {
            $domain = str_replace('http://', 'https://', $domain);
        }

        return $domain . $path;
    }

    /**
     * 引入uc的静态资源.
     * @param $uri
     * @param array $params
     * @return string
     */
    public static function buildUcStaticUrl($uri, $params = [])
    {
        $release_version = StaticAssetsHelper::getReleaseVersion();
        $params = $params + [ "ver" => $release_version ];

        return self::buildUcUrl($uri, $params);
    }

    /**
     * 设置App Id
     * @param int $app_id
     * @throws \Exception
     */
    public static function setAppId($app_id = 0)
    {
        if(self::$app_id) {
            throw new \Exception('不能重复设置APP_ID');
        }
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


    /*客服系统相关URL start **/
    /**
     * 生成客服应用的链接.
     * @param $uri
     * @param array $params
     * @return string
     */
	public static function buildKFUrl($uri, $params = []){
        $path = $uri ? Url::toRoute(array_merge([ $uri ], $params)) : '';
        $domain = \Yii::$app->params['domains']['www'];

        if (CommonService::is_SSL()) {
            $domain = str_replace('http://', 'https://', $domain);
        }

        return $domain.$path;
    }

    /**
     * 生成带有msn的url
     */
    public static function buildKFMSNUrl($uri, $params = []){
        $msn = isset( $params['msn'] )? "/{$params['msn']}":"";
        unset( $params['msn'] );
        return self::buildKFUrl($msn.$uri,$params);
    }
    /**
     * 生成客服应用的静态资源Url
     * @param $uri
     * @param array $params
     * @return string
     */
    public static function buildKFStaticUrl($uri, $params = [])
    {
        $release_version = StaticAssetsHelper::getReleaseVersion();
        $params = $params + [ "ver" => $release_version ];
        $path = $uri ? Url::toRoute(array_merge([ $uri ], $params)) : '';
        $domain = \Yii::$app->params['domains']['www'];

        if (CommonService::is_SSL()) {
            $domain = str_replace('http://', 'https://', $domain);
        }

        return $domain.$path;
    }

    /**
     * 生成客服商户端的url.
     * @param $uri
     * @param array $params
     * @return string
     */
    public static function buildKFMerchantUrl($uri, $params = []){
        $path = $uri ? Url::toRoute(array_merge([ $uri ], $params)) : '';
        $domain = \Yii::$app->params['domains']['merchant'];

        if (CommonService::is_SSL()) {
            $domain = str_replace('http://', 'https://', $domain);
        }

        return $domain.$path;
    }

    /**
     * 生成客服聊天端url.
     * @param $uri
     * @param array $params
     * @return string
     */
    public static function buildKFCSUrl($uri, $params = [])
    {
        $path = $uri ? Url::toRoute(array_merge([ $uri ], $params)) : '';
        $domain = \Yii::$app->params['domains']['cs'];

        if (CommonService::is_SSL()) {
            $domain = str_replace('http://', 'https://', $domain);
        }

        return $domain.$path;
    }


    /**
     * 生成客服聊天端静态地址.
     * @param $uri
     * @param array $params
     * @return string
     */
    public static function buildKFCSStaticUrl($uri, $params = [])
    {
        return self::buildKFStaticUrl($uri, $params);
    }
    /*客服系统相关URL end **/


    /**
     * Author: apanly
     * 获取static cdn目录的静态资源，css 和  js
     * @param $path
     * @param array $params
     * @return string
     */
    public static function buildStaticUrl($path, $params = [])
    {
        $domain = \Yii::$app->params['domains']['static'];

        $path = $path == '' ? $path :  Url::toRoute(array_merge([$path], $params));

        if (CommonService::is_SSL()) {
            $domain = str_replace('http://', 'https://', $domain);
        }

        return $domain . $path;
    }

    /**
     * 生成七牛云的链接
     * @param $bucket
     * @param $img_key
     * @param array $params
     * @return string
     */
    public static function buildPicStaticUrl($bucket ,$img_key,$params = [])
    {
        $bucket = $bucket ? $bucket : "pic3";

        $config = \Yii::$app->params['cdn'];

        $domain = isset($config[$bucket]) ? $config[$bucket]['http'] : 'http://cdn.static.test.jiatest.cn';

        $url = $domain . '/' . $img_key;

        $width = isset($params['w'])?$params['w']:0;
        $height = isset($params['h'])?$params['h']:0;

        if( !$height && !$width ){
            return $url;
        }

        if(isset($params['view_mode'])){
            $url .= "?imageView2/".$params['view_mode'];
        }else{
            $url .= "?imageView2/1";
        }

        if($width){
            $url .= "/w/".$width;
        }

        if($height){
            $url .= "/h/".$height;
        }

        $url .= "/interlace/1";
        return $url;
    }
} 
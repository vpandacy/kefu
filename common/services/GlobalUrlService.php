<?php
namespace common\services;

use common\components\helper\StaticAssetsHelper;
use yii\helpers\Url;

class GlobalUrlService {

    /**
     * 生成www端用户
     * @param $uri
     * @param array $params
     * @return string
     */
	public static function buildWwwUrl(  $uri, $params = [] )
    {
		$path = Url::toRoute(array_merge([ $uri ], $params));
		$domain = \Yii::$app->params['domains']['www'];
		return $domain.$path;
	}

    /**
     * 商户url.
     * @param $uri
     * @param array $params
     * @return string
     */
    public static function buildMerchantUrl(  $uri, $params = [] )
    {
        $path = Url::toRoute(array_merge([ $uri ], $params));
        $domain = \Yii::$app->params['domains']['merchant'];
        return $domain.$path;
    }

    /**
     * 生成商户sn.
     * @param $merchant_sn
     * @param $uri
     * @param array $params
     * @return string
     */
	public static function buildCsUrl( $merchant_sn, $uri, $params = [])
    {
	    $uri = "/{$merchant_sn}".$uri;
		$path = Url::toRoute(array_merge([ $uri ], $params));
		$domain = \Yii::$app->params['domains']['cs'];
		return $domain.$path;
	}

    /**
     * Author: Vincent
     * 加载www应用的js 和 css
     * @param $uri
     * @param array $params
     * @return string
     */
	public static function buildWwwStaticUrl(  $uri, $params = [] )
    {
        $release_version = StaticAssetsHelper::getReleaseVersion();
		$params = $params + [ "ver" => $release_version ];
		$path = Url::toRoute(array_merge([ $uri ], $params));
		$domain = \Yii::$app->params['domains']['www'];
		return $domain.$path;
	}

    /**
     * Author: Vincent
     * 加载www应用的js 和 css
     * @param $uri
     * @param array $params
     * @return string
     */
    public static function buildUcStaticUrl(  $uri, $params = [] )
    {
        $release_version = StaticAssetsHelper::getReleaseVersion();
        $params = $params + [ "ver" => $release_version ];
        $path = Url::toRoute(array_merge([ $uri ], $params));
        $domain = \Yii::$app->params['domains']['uc'];
        return $domain.$path;
    }

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
        $path = Url::toRoute(array_merge([$path], $params));
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
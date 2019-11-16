<?php
namespace common\services;

use yii\helpers\Url;

class GlobalUrlService {

	public static function buildWwwUrl(  $uri, $params = [] ){
		$path = Url::toRoute(array_merge([ $uri ], $params));
		$domain = \Yii::$app->params['domains']['www'];
		return $domain.$path;
	}

    public static function buildMerchantUrl(  $uri, $params = [] ){
        $path = Url::toRoute(array_merge([ $uri ], $params));
        $domain = \Yii::$app->params['domains']['merchant'];
        return $domain.$path;
    }


	public static function buildCsUrl( $merchant_sn, $uri, $params = []){
	    $uri = "/{$merchant_sn}".$uri;
		$path = Url::toRoute(array_merge([ $uri ], $params));
		$domain = \Yii::$app->params['domains']['cs'];
		return $domain.$path;
	}

	public static function buildWwwStaticUrl(  $uri, $params = [] ){
        $release_version = defined("RELEASE_VERSION") ? RELEASE_VERSION : time();
		$params = $params + [ "ver" => $release_version ];
		$path = Url::toRoute(array_merge([ $uri ], $params));
		$domain = \Yii::$app->params['domains']['www'];
		return $domain."/static".$path;
	}

    public static function buildPicStaticUrl($bucket ,$img_key,$params = []){
        $bucket = $bucket?$bucket:"pic3";
        $url = "//{$bucket}.s.360zhishu.cn/".$img_key;

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

    public static function buildNullUrl(){
    	return "javascript:void(0);";
	}
} 
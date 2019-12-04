<?php
namespace common\services;


class QiniuService extends BaseService{

    private static function getConfig(){
        return \Yii::$app->params['cdn']['qiniu_config'];
    }

    public static function getUploadKey($key, $bucket = "hsh",$policy = null, $strictPolicy = true,$expires = 3600 ){

        $config = self::getConfig();
        $auth   = new \Qiniu\Auth($config['ak'], $config['sk']);

        if( !isset( $config['bucket'][ $bucket ]  ) ) {
            return false;
        }
        $policy = ( is_array( $policy ) && $policy )?$policy:null;
        $bucket = $config['bucket'][ $bucket ];
        return $auth->uploadToken($bucket, $key,$expires, $policy, $strictPolicy);
    }
}
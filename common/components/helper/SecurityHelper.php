<?php

namespace common\components\helper;

class SecurityHelper
{
    public static function encrypt( $str,$key = "hsh"){
        return \Yii::$app->getSecurity()->encryptByPassword($str, $key);
    }

    public static function decrypt( $str,$key = "hsh"){
        return \Yii::$app->getSecurity()->decryptByPassword($str, $key);
    }
}
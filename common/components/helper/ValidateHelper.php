<?php

namespace common\components\helper;

class ValidateHelper extends  \common\services\BaseService {
    public static function validEmail( $email ){
        return  filter_var( $email,FILTER_VALIDATE_EMAIL );
    }

    public static function validPhone( $phone ){
        $pattern = "#^1[0-9]{10}$#";
        return preg_match($pattern, $phone);
    }

    public static function validMobile( $mobile ){
        return preg_match("#^1[0-9]{10}$#",$mobile);
    }

    public static function validUrl( $url ){
        return filter_var( $url,FILTER_VALIDATE_URL);
    }

    public static function validLength( $param,$min,$max ){
        $len = mb_strlen($param);
        return $len < $min || $len > $max ? false : true;
    }

    public static function validIsEmpty($params = ''){
        return empty($params);
    }

    /**
     * Author: Vincent
     * @param $params [
     *      [  "value":"xxx","rule":"email","require":true  ],
     *      [  "value":"xxx","rule":"email"   ],
     *      [  "value":"xxx","rule":"email"   ],
     * ]
     */
    public static function valid( $params ){

    }

}
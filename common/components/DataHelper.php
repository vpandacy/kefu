<?php
namespace common\components;

class DataHelper {
    /**
     * 转义字符串.
     * @param $value
     * @return string
     */
    public static function encode($value)
    {
        return htmlspecialchars($value);
    }

    /**
     * 数组进行转义.
     * @param $data
     * @param array $ignore
     * @return array
     */
    public static function encodeArray($data, $ignore = [])
    {
        if(!is_array($data)) {
            return $data;
        }

        foreach($data as $key => $val) {
            if(in_array($key, $ignore)) {
                continue;
            }

            if(is_array($val)) {
                $val = self::encodeArray($val);
            }

            if(is_string($val)) {
                $val = self::encode($val);
            }

            $data[$key] = $val;
        }

        return $data;
    }


    public static function getGuestNumber( $uuid ){
        return mb_substr($uuid,-12);
    }
}
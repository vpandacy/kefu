<?php
namespace common\components\helper;

use common\components\ip\IPDBQuery;
use itbdw\Ip\IpLocation;

class IPHelper {
    /**
     * 获取客户端IP
     */
    public static function getClientIP()
    {
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        return isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '';
    }

    /**
     * 获取ip的详情
     * {  "ip": "117.136.89.105", "country": "中国", "province": "湖南","city": "常德市","county": "","isp": "移动","area": "中国湖南常德市移动"
    }
     */
    public static function getIpInfo( $ip ){
        $arr = IPDBQuery::find( $ip );
        $info = [
            "country" => $arr[0]??"",
            "province" => $arr[1]??"",
            "city" => $arr[2]??""
        ];
        $special_city = false;
        if( isset( $arr[1] ) && isset( $arr[2] ) && $arr[1] == $arr[2] ){
            $special_city = true;
        }

        if( isset( $arr[1] ) && $arr[1]){
            $info["province"] .= "省";
        }

        if( isset( $arr[2] ) && $arr[2]){
            $info["city"] .= "市";
        }

        if( $special_city  ){
            unset( $info["province"] ) ;
        }else if( !$special_city && !$info['city'] ){
            $arr2 = IpLocation::getLocation( $ip );
            $info['city'] = $arr2['city']??'';
        }
        return $info;

    }
}
<?php
namespace common\services\chat;

use common\components\ip\IPDBQuery;
use common\models\merchant\City;
use common\services\BaseService;

class GuestService extends BaseService
{
    /**
     * 获取省市信息.
     * @param string $client_ip
     * @return array
     */
    public static function getProvinceByClientIP($client_ip)
    {
        $address_info = IPDBQuery::find($client_ip);

        list($province, $city, $address) = (array) $address_info;

        $province_info  = [
            'province_id'   =>  0,
            'city_id'       =>  0
        ];

        if(strpos($province, '局域网') !== false || strpos($province,'本地') !== false) {
            return $province_info;
        }

        // 根据城市来查询对应的信息.
        $province = City::findOne(['province'=>$province]);

        if(!$province) {
            return $province_info;
        }

        $province_info['province_id'] = $province['id'];
        $city = City::findOne(['city'=>$city]);

        if(!$city) {
            return $province_info;
        }

        $province_info['city_id'] = $city['id'];
        return $province_info;
    }

    /**
     * 根据referer获取渠道
     * @param string $url
     * @return int
     */
    public static function getRefererSidByUrl( $url = null ){
        $sid = 0;
        $url_params = parse_url($url);
        $host = null;
        if (isset($url_params['host'])) {
            $host = strtolower( $url_params['host'] );
        }else{
            $host = strtolower( $url );
        }

        try{
            if( mb_stripos( $host,"baidu.com" ) !== false ){
                $sid = "10";//判断属于百度的
            }elseif( mb_stripos( $host,"qq.com" ) !== false ){
                $sid = "23";//判断属于广点通的
            }elseif ( mb_stripos( $host,"sogou.com" )  !== false ){
                $sid = "12";//判断属于搜狗的
            }elseif ( mb_stripos( $host,"uc.cn" )  !== false ){
                $sid = "20";//判断属于UC的
            }elseif ( mb_stripos( $host,"wkanx.com" )  !== false ){
                $sid = "18";//判断属于WIFI万能钥匙的
            }elseif ( mb_stripos( $host,"kuaishou.com" )  !== false ){
                $sid = "22";//判断属于快手的
            }elseif ( mb_stripos( $host,"360.cn" )  !== false
                || mb_stripos( $host,"so.com" )  !== false ){
                $sid = "11";//判断属于360的
            }elseif ( mb_stripos( $host,"vivo" )  !== false ){
                $sid = "16"; //判断属于vivo的
            }elseif ( mb_stripos( $host,"oppomobile.com" )  !== false ){
                $sid = "15";//判断属于oppo的
            }elseif ( mb_stripos( $host,"toutiao.com" )  !== false ||
                mb_stripos( $host,"chengzijianzhan.com" )  !== false){
                $sid = "14";//判断属于头条的
            }elseif ( mb_stripos( $host,"sm.cn" )  !== false ){
                $sid = "13";//判断属于神马的
            }elseif ( mb_stripos( $host,"aiclk.com" )  !== false ){
                $sid = "19";//判断属于趣头条的
            }elseif ( mb_stripos( $host,"yidianzixun.com" )  !== false ){
                $sid = "21";//判断属于一点资讯的
            }

            if( $sid ){
                $sid = "{$sid}0";
            }
        }catch (\Exception $e){

        }

        return $sid;
    }
}
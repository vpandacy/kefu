<?php
namespace common\services\applog;

use common\components\helper\DateHelper;
use common\components\helper\UtilHelper;
use common\components\ip\IPDBQuery;
use common\models\logs\AppAccessLog;
use common\models\logs\AppErrLogs;
use common\models\logs\AppGuestLog;
use common\models\logs\CsLoginLogs;
use Yii;

class AppLogService
{

    public static function addErrLog($appname, $content)
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $model_app_logs = new AppErrLogs();
        $model_app_logs->app_name = $appname;
        $model_app_logs->content = ( mb_strlen( $content ) > 5000 ) ?mb_substr($content,0,5000,"utf-8"):$content;
        $model_app_logs->request_uri = $uri;
        $model_app_logs->referer = $referer;
        $model_app_logs->ip = UtilHelper::getClientIP();

        if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
            $model_app_logs->ua = $_SERVER['HTTP_USER_AGENT'];
        }

        $model_app_logs->cookies = var_export($_COOKIE,true);
        $model_app_logs->created_time = date("Y-m-d H:i:s");
        $model_app_logs->save(0);
        return true;
    }

    public static function addAccessLog( $staff_info){
        $get_params = \Yii::$app->request->get();
        $post_params = \Yii::$app->request->post();
        $target_url = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
        $ignore_urls = [
            "oa/log",
            "oa/profile/index",
            "oa/default",
            "oa/news/ops",
            'oa/staff/vlogin'
        ];
        $ignore_url = "#".implode("|",$ignore_urls)."#";
        if( $target_url && preg_match($ignore_url,$target_url) ){
            return true;
        }
        $referer = Yii::$app->request->getReferrer();
        $ua = Yii::$app->request->getUserAgent();
        $query_params = array_merge($get_params,$post_params);
        if( count( $query_params ) > 10 ){
            $query_params =  array_rand( $query_params ,10);
        }
        unset($query_params['password']);
//        $max_field_val = 150;
//        if( $query_params ){
//            foreach ( $query_params as $_query_key =>  $_query_item){
//                $_query_item = is_array($_query_item) ? Json::encode($_query_item) : $_query_item;
//                if( mb_strlen( $_query_item ,"utf-8") > $max_field_val ){
//                    $query_params[ $_query_key ] = mb_substr( $_query_item,0,$max_field_val,"utf-8");
//                }
//            }
//        }

        $access_log = new AppAccessLog();
        $access_log->merchant_id = $staff_info?$staff_info['merchant_id']:0;
        $access_log->staff_id = $staff_info?$staff_info['id']:0;
        $access_log->staff_name = $staff_info?$staff_info['name']:'';
        $access_log->referer_url = $referer?$referer:'';
        $access_log->target_url = $target_url;
        $access_log->query_params = json_encode( $query_params );
        $access_log->ua = $ua?$ua:'';
        $access_log->ip = UtilHelper::getClientIP();
        $ips = explode(",",$access_log->ip);
        $access_log->ip_desc = implode(" ",IPDBQuery::find( trim( $ips[0] ) ) );
        $access_log->created_time = date("Y-m-d H:i:s");
        return $access_log->save(0);

    }

    /**
     * 添加登录日志.
     * @param $merchant_id
     * @param $staff_id
     * @param int $type 0为登录,1为退出.
     * @param array $params
     * @return bool
     */
    public static function addLoginLog($merchant_id, $staff_id, $type = 0, $params = [])
    {
        if(!$type) {
            $logs = new CsLoginLogs();
            $logs->setAttribute('login_time',DateHelper::getFormatDateTime());
        }else{
            $logs = CsLoginLogs::find()
                ->where(['merchant_id'=>$merchant_id, 'staff_id'=>$staff_id])
                ->orderBy(['id'=>SORT_DESC])
                ->one();

            if(!$logs) {
                return false;
            }

            $logs->setAttribute('logout_time', DateHelper::getFormatDateTime());
        }

        if(!$logs) {
            return false;
        }

        $data = array_merge([
            'merchant_id'   =>  $merchant_id,
            'staff_id'      =>  $staff_id
        ], $params);

        $logs->setAttributes($data);

        return $logs->save();
    }

    public static function addGuestLog(){
        $get_params = \Yii::$app->request->get();
        $referer = Yii::$app->request->getReferrer();
        $ua = Yii::$app->request->getUserAgent();
        $cookies = Yii::$app->request->cookies;
        $cookies_config = Yii::$app->params['cookies']['guest'];
        $cookie_str = var_export($_COOKIE,true);
        $max_length = 600;

        $uuid = $cookies->getValue( $cookies_config['name'],"" );
        if( !$uuid ){
            $uuid = $get_params['uuid']??"";
        }
        $guest_log = new AppGuestLog();
        $guest_log->cookie = ( mb_strlen($cookie_str,"utf-8") > $max_length )?(mb_substr($cookie_str,0,$max_length)):$cookie_str ;
        $guest_log->uuid = $uuid;
        $guest_log->referer = $referer?$referer:'';
        $guest_log->ua = $ua?$ua:'';
        $guest_log->ip = UtilHelper::getClientIP();
        $guest_log->save(0);
        return true;
    }
}

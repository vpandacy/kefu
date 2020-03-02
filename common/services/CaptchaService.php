<?php

namespace common\services;

use common\components\helper\UtilHelper;
use common\models\uc\QueueCaptcha;
use common\services\ConstantService;


/**
 * 验证码类
 */
class CaptchaService extends BaseService
{
    public static function checkCaptcha($account, $type, $captcha)
    {
        $account = trim($account);
        $captcha = str_replace(' ', '', $captcha);

        $captcha = QueueCaptcha::findOne([ 'account' => $account,"type" => $type, 'captcha' => $captcha]);
        if ($captcha && strtotime($captcha->expires_at) >= time()) {
            $captcha->expires_at = date("Y-m-d H:i:s", time() - 1);
            $captcha->status = ConstantService::$default_status_true;
            $captcha->save();
            return true;
        }

        return false;
    }

    public static function getLastCaptcha($account, $type)
    {
        return QueueCaptcha::find()->where(['account' => $account, "type" => $type])
            ->orderBy(['id' => SORT_DESC])->limit(1)->one();
    }

    public static function geneCustomCaptcha($account, $type)
    {
        $last = self::getLastCaptcha($account, $type);

        if ($last && (time() - strtotime($last->created_time) < 60)) {
            return self::_err("发送得太快啦,请稍后在发送~~");
        }

        $ip = UtilHelper::getClientIP();
//        if( !self::allow_send_captcha($account, $type,$ip) )
//        {
//            return self::_err( "发送数量超过限制~~" );
//        }
        $captcha = (string)rand(10000, 99999);

        $ret = SmsService::send($account,$captcha,'default',$ip);

        if( $ret ){
            $model_captcha = new QueueCaptcha();
            $model_captcha->type = $type;
            $model_captcha->account = $account;
            $model_captcha->captcha = $captcha;
            $model_captcha->ip = $ip;
            $model_captcha->status = 0;
            $model_captcha->expires_at = date("Y-m-d H:i:s",time() + 60 * 5);
            $ret = $model_captcha->save( 0 );
        }
        return $ret;
    }

    private static function allow_send_captcha($account, $type ,$ip = '')
    {
        $time_limits = [
            1 => 2,
            5 => 3,
            60 => 5,
            1440 => 8
        ];//minute => count

        //当天限制
        $captcha_records = QueueCaptcha::find()
            ->where([ "account" => $account,"type" =>  $type ])
            ->andWhere(['>=', 'created_time', date("Y-m-d 00:00:00")])
            ->orderBy([ 'id' => SORT_DESC ])
            ->limit(8)
            ->all();

        foreach ($time_limits as $minute => $count) {
            if (isset($captcha_records[$count - 1]) && strtotime($captcha_records[$count - 1]['created_time']) > time() - $minute * 60) {
                return false;
            }
        }

        if ($ip) {
            $captcha_records = QueueCaptcha::find()
                ->where(['ip' => $ip])
                ->andWhere(['>=', 'created_time', date("Y-m-d 00:00:00")])
                ->orderBy([ "id" => SORT_DESC ])
                ->limit(8)
                ->all();

            foreach ($time_limits as $minute => $count) {
                if (isset($captcha_records[$count - 1]) && strtotime($captcha_records[$count - 1]['created_time']) > time() - $minute * 60) {
                    return false;
                }
            }
        }
        return true;
    }
}
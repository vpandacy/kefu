<?php

namespace common\services;

use common\models\uc\QueueSms;

class SmsService extends BaseService {
	public static function send( $mobile, $content,$channel = 'default',$ip = '',$sign='' ) {
		if( !$channel ) {
			$channel = 'default';
		}

		$sms_params= [
			'mobile' =>$mobile,
			'content' => $content,
			'ip' => $ip,
			'channel' => $channel,
			'sign' => $sign
		];
		//加入短信发送队列中去

		self::Log(sprintf("DO Insert Queue %s\t mobile:%s , content: %s ",date('Y-m-d H:i:s'),$mobile,$content ));
		//self::doSend($mobile, $content, $channel, $ip, $sign);
        return self::addSmsQueue(  $sms_params );
	}


	public static function doSend($mobile, $content, $channel = 'default',$ip='',$sign='') {
		if( in_array($ip,['223.73.110.248' ] ) ) {
			return false;
		}

		if( !self::recent_history_check($ip) ) {
			return false;
		}

		if( empty($mobile) ) {
			self::Log( "{$mobile} is not mobile,no mobile number,quit.");
			return false;
		}
		// @todo 这里要注意下.
        $sms_config =  \Yii::$app->params['sms'];
		$ret = "success";
		switch( $channel ) {
			case "default":
			default:

				break;
		}

		return $ret;
	}


	public static function addSmsQueue( $sms_params = []){
		$model_sms_history = new QueueSms();
		$model_sms_history->mobile = isset( $sms_params['mobile'] )?$sms_params['mobile']:'';
		$model_sms_history->sign = isset( $sms_params['sign'] )?$sms_params['sign']:'';
		$model_sms_history->content = isset( $sms_params['content'] )?$sms_params['content']:'';
		$model_sms_history->channel = isset( $sms_params['channel'] )?$sms_params['channel']:'';
		$model_sms_history->status = isset( $sms_params['status'] )?$sms_params['status']:-2;
		$model_sms_history->ip = isset( $sms_params['ip'] )?$sms_params['ip']:'';
		$model_sms_history->created_time = $model_sms_history->updated_time  = date("Y-m-d H:i:s");
		return $model_sms_history->save(0);
	}


	public static function Log($txt){
		$log = \Yii::$app->getRuntimePath().DIRECTORY_SEPARATOR."sms_".date("Y-m-d").".log";
		file_put_contents($log, '[' . date('Y-m-d H:i:s') .']'. $txt."\n",FILE_APPEND);
	}

	/**
	 * ip限制
	 * 1分钟 只能发5次
	 * 2分钟 只能发8次
	 * 3分钟 只能发10次
	 */
	private static function recent_history_check($ip){
        if($ip)
        {
            $time = time();
            $hash_key = sprintf("SMS:send:%s:%s",date('Hi',$time),$ip);
            \Yii::$app->list_001->incr($hash_key);
            \Yii::$app->list_001->setTimeout($hash_key,600);
            $total = 0;
            for($i=4;$i>=0;$i--)
            {
                $hash_key = sprintf("SMS:send:%s:%s",date('Hi',$time-60*$i),$ip);
                $total = $total + intval(\Yii::$app->list_001->get($hash_key));
            }
            if($total >= 60)
            {
                return false;
            }
        }
        return true;
	}


    /**
     * 解析数据格式.
     * @param string $ret 获取的返回结果.
     * @param string $format 返回结果的格式.
     * @return array
     */
    public static function parseResult($ret,$format = 'xml')
    {
        $data = [];
        switch ($format)
        {
            case 'xml':
                $data = (array)simplexml_load_string($ret);
                break;
            case 'json':
                $data = json_decode($ret,true);
                break;
        }

        return $data;
    }


}
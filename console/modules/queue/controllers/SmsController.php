<?php

namespace console\modules\queue\controllers;

use common\models\uc\QueueSms;
use common\services\SmsService;
use console\controllers\BaseController;

class SmsController extends  BaseController {
	/**
	 * php yii queue/sms/run
	 */
	public function actionRun(){
		$list = QueueSms::find()->where([ 'status' => -2  ])
            ->orderBy([ 'id' =>SORT_ASC ])->limit( 10 )->all();
		if( !$list ){
			return $this->echoLog( 'no data to handle ~~' );
		}

		//查询未发送的短信数量，超过设定值触发警报
        $sms_config =  \Yii::$app->params['sms'];
		$sms_warning = $sms_config['pending_warning'] ? $sms_config['pending_warning'] : 100;
		$pending_count = QueueSms::find()->where(['status' => -2])->count();

		if ($pending_count > $sms_warning) {
		    $title = '短信警报通知';
		    $content = "短信警报<br/>待发送短信已超出预设值{$sms_warning}<br/>当前待发送数量为{$pending_count}<br/>{请及时查看并处理}";
//			MailService::addEmailQueue( $title,$content, $sms_config['warning_to']);
		}

        foreach( $list as $_sms_info ) {
            $this->echoLog("queue_id:{$_sms_info['id']}");
            $_sms_info->status = -1;
            if( !$_sms_info->save( 0 ) ){
                continue;
            }

            $tmp_ret = SmsService::doSend( $_sms_info['mobile'],$_sms_info['content'],$_sms_info['channel'],$_sms_info['ip'],$_sms_info['sign'] );
            $_sms_info->status = $tmp_ret?1:0;
            $_sms_info->return_msg = $tmp_ret?$tmp_ret:SmsService::getLastErrorMsg();
            $_sms_info->save( 0 );
            //$tmp_ret = SmsService::parseResult($tmp_ret);
            if( !$tmp_ret ){
                $this->echoLog( $_sms_info->return_msg );
            }
        }

		return $this->echoLog("it's over ~~");
	}
}
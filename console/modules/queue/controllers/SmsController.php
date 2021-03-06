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

        foreach( $list as $_sms_info ) {
            $this->echoLog("queue_id:{$_sms_info['id']}");
            $_sms_info->status = -1;
            if( !$_sms_info->save( 0 ) ){
                continue;
            }

            $tmp_ret = SmsService::doSend( $_sms_info['mobile'],$_sms_info['content'],$_sms_info['channel'],$_sms_info['ip'],$_sms_info['sign'] );
            $_sms_info->setAttributes([
                'status'    =>  $tmp_ret ? 1 : 0,
                'return_msg'=>  $tmp_ret ? $tmp_ret : SmsService::getLastErrorMsg()
            ]);
            $_sms_info->save( 0 );
            //$tmp_ret = SmsService::parseResult($tmp_ret);
            if( !$tmp_ret ){
                $this->echoLog( $_sms_info->return_msg );
            }
        }

		return $this->echoLog("it's over ~~");
	}
}
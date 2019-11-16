<?php
namespace console\controllers;

class BaseController extends  \yii\console\Controller {
    public function echoLog($msg){
        $this->stdout( date("Y-m-d H:i:s")." ".$msg."\r\n" );
        return true;
    }


    public function setCur( $file_name ,$id = 0){
        $log_path = \Yii::$app->params['log_path'];
        $file_path = $log_path.$file_name;
        @file_put_contents( $file_path,$id );
        return true;
    }

    public function getCur( $file_name ){
        $log_path = \Yii::$app->params['log_path'];
        $file_path = $log_path.$file_name;
        $cur = @file_get_contents( $file_path );
        if( !$cur ){
            $cur = 0;
        }
        return trim($cur);
    }


} 
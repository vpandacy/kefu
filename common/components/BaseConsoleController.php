<?php
/**
 * Class BaseController
 * Author: Vincent
 * WeChat: apanly
 * CreateTime: 2019/7/11 8:17 PM
 */
namespace common\components;

class BaseConsoleController extends  \yii\console\Controller
{
    public function echoLog($msg){
        echo date("Y-m-d H:i:s")." ".$msg."\r\n";
        return true;
    }
}
<?php

namespace www\controllers;
use common\components\BaseWebController;
use common\services\applog\AppLogService;
class ErrorController extends BaseWebController
{
    public function actionHandler(){
        $error = \Yii::$app->errorHandler->exception;
        $code = $error->getCode();
        $msg = $error->getMessage();
        $file = $error->getFile();
        $line = $error->getLine();
        $host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:"";
        $uri = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:"";
        $referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
        $url = $host.$uri;

        $query_params  = $_POST;
        unset( $query_params['password']);

        $err_msg = $msg . " [file: {$file}][line: {$line}][err code:$code.][url:{$url}][referer:{$referer}][post:".http_build_query($query_params)."]";
        AppLogService::addErrLog( \Yii::$app->id ,$err_msg );
        return $err_msg;
    }

    public function actionCapture(){
        $referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
        $sc = $this->post("sc","app");
        $url = $this->post("url","");
        $message = $this->post("message","");
        $error = $this->post("error","");
        $err_msg = "JS ERROR：[url:{$referer}],[js_file:{$url}],[error:{$message}],[error_info:{$error}]";
        AppLogService::addErrLog("js-{$sc}",$err_msg);
        return $this->renderJSON();
    }
}
<?php

namespace www\controllers;
use yii\web\Controller;

class ErrorController extends Controller
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

        $err_msg = $msg . " [file: {$file}][line: {$line}][err code:$code.][url:{$url}][referer:{$referer}][post:".http_build_query($_POST)."]";
        return $err_msg;
    }

    public function actionError()
    {
        return $this->render('error');
    }
}
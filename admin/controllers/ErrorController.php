<?php

namespace admin\controllers;

use common\components\BaseWebController;
use common\services\applog\AppLogService;
use common\services\GlobalUrlService;

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

        $err_msg = $msg . " [file: {$file}][line: {$line}][err code:$code.][url:{$url}][referer:{$referer}][post:".http_build_query($_POST)."]";
        AppLogService::addErrLog( \Yii::$app->id ,$err_msg );
        $reback_url = GlobalUrlService::buildAdminUrl("/");

        $this->layout = false;
        return $this->render("index",[
            "title" => "Page Not Found",
            "msg" => "404警告！ 很不幸，您探索了一个未知领域！",
            "reback_url" => $reback_url
        ]);
    }

    public function actionCapture(){
        $referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
        $url = $this->post("url","");
        $message = $this->post("message","");
        $error = $this->post("error","");
        $err_msg = "JS ERROR：[url:{$referer}],[js_file:{$url}],[error:{$message}],[error_info:{$error}]";
        AppLogService::addErrLog("app-js",$err_msg);
        return $this->renderJSON();
    }
}

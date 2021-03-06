<?php
namespace uc\controllers;

use common\services\applog\AppLogService;
use uc\controllers\common\BaseController;

class ErrorController extends BaseController
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

    public function actionError()
    {
        return $this->render('error');
    }

    /**
     * 收集消息错误信息.
     */
    public function actionCaptcha()
    {
        $message = $this->post('message','');
        $source = $this->post('source','');
        $stack  = $this->post('stack','');
        $lineno = $this->post('lineno','');
        $referer = $this->post('referer','');
        $request_uri = $this->post('request_uri','');
        $ua = $this->post('ua','');
        $content = "[file: $source][line: $lineno][url: $request_uri][referer: $referer][ua:$ua][stack: $stack][message: $message]";
        AppLogService::addErrLog('app-js', $content);
    }
}
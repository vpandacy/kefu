<?php

namespace common\components;


use yii\helpers\Html;
use yii\web\Controller;
use Yii;
use yii\web\HttpException;
use yii\web\Response;


class BaseWebController extends Controller
{
    public $enableCsrfValidation = false;

    public $page_size = 30;

    public function setTitle($title = "")
    {
        $this->getView()->title = $title;
    }

    protected function renderJSON($data = [], $msg = "操作成功~~", $code = 200)
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data   = [
            'msg'    => $msg,
            'code'   => $code,
            'data'   => $data,
            'req_id' => $this->geneReqId()
        ];

        return $response;
    }

    protected function renderErrJSON($msg = "操作失败~~", $data = [])
    {
        return $this->renderJSON($data, $msg, -1);
    }


    protected function geneReqId()
    {
        return uniqid();
    }

    public function post($key, $default = "")
    {
        return Yii::$app->request->post($key, $default);
    }


    public function get($key, $default = "")
    {
        return Yii::$app->request->get($key, $default);
    }

    protected function setCookie($name, $value, $expire = 0, $domain = '', $path = '/')
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name'   => $name,
            'value'  => $value,
            'expire' => $expire ? (time() + $expire) : $expire,
            'domain' => $domain,
            'path'   => $path,
        ]));
    }

    protected function getCookieObject($name, $value, $expire = 0, $domain = '')
    {
        return new \yii\web\Cookie([
            'name'   => $name,
            'value'  => $value,
            'expire' => $expire ? (time() + $expire) : $expire,
            'domain' => $domain
        ]);
    }

    protected function getCookie($name, $default_val = '')
    {
        $cookies = Yii::$app->request->cookies;
        return $cookies->getValue($name, $default_val);
    }


    protected function removeCookie($name, $domain = '', $path ='/')
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name'   => $name,
            'value'  => "",
            'expire' => 1,
            'domain' => $domain,
            'path'   => $path
        ]));
    }

    protected function isAjax()
    {
        return Yii::$app->request->isAjax;
    }

    protected function isGetMethod()
    {
        return Yii::$app->request->isGet;
    }
}

class BaseWebException extends HttpException
{
    protected $code;

    const OK = 'OK';
    const UNKNOWN = 'Unknown';
    const OBJECT_NOT_FOUND = 'ObjectNotFound';
    const METHOD_NOT_ALLOWED = 'MethodNotAllowed';
    const AUTHENTICATION_FAILED = 'AuthenticationFailed';
    const INVALID_INPUT_FORMAT = 'InvalidInputFormat';
    const DATA_CONFLICT = 'DataConflict';
    const QUOTA_NOT_ENOUGH = 'QuotaNotEnough';
    const THIRD_PLATFORM_SERVICE_ERROR = 'ThirdPlatformServiceError';

    static $codeMap = array(
        self::OK                           => ['200 OK', ''],
        self::OBJECT_NOT_FOUND             => ['404 Not Found', 'requested object does not exists'],
        self::METHOD_NOT_ALLOWED           => ['405 Method Not Allowed', 'method not allowed'],
        self::UNKNOWN                      => ['500 Internal Server Error', 'unknown error occurred'],
        self::AUTHENTICATION_FAILED        => ['403 Forbidden', ' authentication failed'],
        self::INVALID_INPUT_FORMAT         => ['400 Bad Request', 'invalid input format'],
        self::DATA_CONFLICT                => ['409 Conflict', 'data conflict'],
        self::QUOTA_NOT_ENOUGH             => ['403 Forbidden', 'quota not enough'],
        self::THIRD_PLATFORM_SERVICE_ERROR => ['503 Forbidden', 'third platform service error'],
    );

    function __construct($code, $msg = "")
    {
        $this->code    = $code;
        $this->message = $msg;
    }

    function getOriginsMessage()
    {
        return $this->message;
    }

    static function objectNotFound($msg = '')
    {
        return new self(self::OBJECT_NOT_FOUND, $msg);
    }

    static function methodNotAllowed($msg = '')
    {
        return new self(self::METHOD_NOT_ALLOWED, $msg);
    }

    static function authenticationFailed($msg = '')
    {
        return new self(self::AUTHENTICATION_FAILED, $msg);
    }

    static function invalidInputFormat($msg = '')
    {
        return new self(self::INVALID_INPUT_FORMAT, $msg);
    }

    static function dataConflict($msg = '')
    {
        return new self(self::DATA_CONFLICT, $msg);
    }

    static function quotaNotEnough($msg = '')
    {
        return new self(self::QUOTA_NOT_ENOUGH, $msg);
    }

    static function thirdPlatformServiceError($msg = '')
    {
        return new self(self::THIRD_PLATFORM_SERVICE_ERROR, $msg);
    }

    static function getHttpCode($code)
    {
        return isset(self::$codeMap[$code]) ? self::$codeMap[$code][0] : '400 Bad Request';
    }
}

<?php
namespace common\services;

class ConstantService extends BaseService
{
    public static $response_code_fail = -1;

    public static $response_code_page_success = 0;

    /**
     * 配合layui的接口请求.
     * @var int
     */
    public static $response_code_success = 200;

    // 帐号正常.
    public static $default_status_true = 1;

    // 帐号异常.
    public static $default_status_false = 0;

    // 定义一些常量ID.
    public static $merchant_app_id = 1;
}
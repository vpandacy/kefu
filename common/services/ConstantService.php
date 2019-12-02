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
}
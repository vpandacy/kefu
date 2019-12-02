<?php
namespace common\services;

class ConstantService extends BaseService
{
    public static $response_fail = -1;

    public static $response_success = 0;

    /**
     * 配合layui的接口请求.
     * @var int
     */
    public static $response_page_success = 200;
}
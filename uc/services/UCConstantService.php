<?php
namespace uc\services;

use common\services\BaseService;

class UCConstantService extends BaseService
{
    /**
     * 注册.
     * @var int
     */
    public static $ws_register = 1;

    /**
     * 网关.
     * @var int
     */
    public static $ws_gateway = 2;

    /**
     * 业务worker.
     * @var int
     */
    public static $ws_busiworker = 3;
}
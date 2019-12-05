<?php
namespace uc\service;

use common\services\BaseService;

class AppService extends BaseService
{
    /**
     * 根据应用来得到对应的app_id
     * @param string $app_name
     * @return false|int|string
     */
    public static function getAppId($app_name = '')
    {
        if(!$app_name) {
            return 0;
        }

        $app_id = array_search($app_name, ConstantService::$app_mapping);

        return !$app_id ? 0 : $app_id;
    }
}
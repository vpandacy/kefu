<?php
namespace www\services;

use common\services\BaseService;

class MerchantConstantService extends BaseService
{
    /**
     * 客服分配方式
     * @var array
     */
    public static $group_distribution_modes = [
        0   =>  '自动分配',
//        1   =>  '手动分配'
    ];

    /**
     * 客服接待规则
     * @var array
     */
    public static $group_reception_rules = [
        0   =>  '人工客服优先',
    ];

    /**
     * 客服接待策略
     * @var array
     */
    public static $group_reception_strategies = [
        0   =>  '风格分组优先',
//        1   =>  '管理员优先'
    ];

    /**
     * 客服分流规则.
     * @var array
     */
    public static $group_shunt_modes = [
        0   =>  '指定客服'
    ];
}
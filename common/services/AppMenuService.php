<?php

namespace common\services;
use uc\services\ConstantService;

class AppMenuService
{
    public static $uc_keys = [ 'staff','rbac','log','attendance','business','sysconf' ];
    public static $uc_sub_keys = ['category'];
    public static $staff_attendance_menus = [
        "title"=> "考勤管理",
        "icon" => "clock-o",
        "sub" => [
            [ "title" => "考勤列表","url" => "/attendance/index" ],
        ]
    ];

    public static $staff_menus = [
        "title" => "员工管理",
        "icon" => "user",
        "sub" => [
            [ "title" => "员工列表","url" => "/staff/index" ],
            [ "title" => "部门列表","url" => "/department/index" ],
            [ "title" => "岗位列表","url" => "/role/index" ],
        ]
    ];

    public static $rbac_menus = [
        "title" => "权限管理",
        "icon" => "lock",
        "sub" => [
            [ "title" => "权限设置","url" => "/action/index" ]
        ]
    ];

    public static $log_menus = [
        "title" => "系统日志",
        "icon" => "desktop",
        "sub" => [
            [ "title" => "访问日志","url" => "/log/index" ],
            [ "title" => "错误日志","url" => "/log/error" ],
            [ "title" => "登录日志","url" => "/log/login" ],
            [ "title" => "邮件队列","url" => "/log/email" ],
            [ "title" => "短信队列","url" => "/log/sms" ],
            [ "title" => "媒体队列","url" => "/log/media" ]
        ]
    ];

    public static $system_config = [
        "title" => "系统配置",
        "icon" => "cog",
        "sub" => [
            [ "title" => "号段管理","url" => "/sysconf/mobile/index" ],
            [ "title" => "功能迭代","url" => "/sysconf/iter-log/index" ],
        ]
    ];

    public static $business_menus = [
        "title" => "商户设置",
        "icon" => "building",
        "sub" => [
            ["title" => "商户设置", "url" => "/saasmerchant/index"],
        ]
    ];

    public static $category_sub_menus = [
         "title" => "分类列表", "url"=> "/base/category/index"
    ];
    public static function getAppMenu($app_api)
    {
        switch ($app_api)
        {
            case ConstantService::$HSH_ADMIN_APPID:
                $menus = self::getAdminMenu();
                break;
            default:
                $menus = [];
                break;
        }
        return $menus;
    }




    public static function getAdminMenu()
    {
        $menus = [
            "log" => [
                "title" => "日志管理",
                "icon" => "desktop",
                "sub" => [
                    [ "title" => "访问日志","url" => "/log/index" ],
                    [ "title" => "错误日志","url" => "/log/error" ],
                    [ "title" => "短信队列","url" => "/log/sms" ],
                ]
            ],
            "merchant" => [
                "title" => "商户管理",
                "icon" => "users",
                "sub" => [
                    [ "title" => "商户列表","url" => "/log/index" ],
                ]
            ],

        ];
        return $menus;
    }
}
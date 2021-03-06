<?php
namespace common\services\uc;

use common\services\BaseService;

use common\services\ConstantService;

class MenuService extends BaseService
{
    /**
     * 获取所有系统的管理操作.根据APP_ID来.
     * @param $app_id
     * @param $uris
     * @return array
     */
    public static function getAllMenu($app_id )
    {
        // 后续增加.
        switch ($app_id) {
            case ConstantService::$merchant_app_id:
                $menus = self::getMerchantUrl();
                break;

            case ConstantService::$admin_app_id:
                $menus = self::getAdminMenu();
                break;
            default:
                $menus = [
                    'left_menu' =>  [],
                    'bar_menu'  =>  []
                ];
        }

        return $menus;
    }

    /**
     * 获取所有的菜单.
     * @param array $urls
     * @return array
     */
    public static function getMerchantUrl()
    {
        $all_menu = [
            'left_menu' =>  self::getLeftMenu(),
            'bar_menu'  =>  self::getBarMenu()
        ];
        $uc_actions = [
            'user', 'sub_user', 'department', 'role', 'action', 'company', 'staff_log'
        ];

        foreach($all_menu['left_menu'] as $key=>$action) {
            if(in_array($key, $uc_actions)) {
                $action['url'] = 'uc/' . $action['url'];
            }

            $all_menu['left_menu'][$key] = $action;
        }

        foreach($all_menu['bar_menu'] as $key=>$sub_menus) {
            foreach($sub_menus as $k => $bar_menu) {
                if(in_array($k, $uc_actions)) {
                    $bar_menu['url'] = 'uc/' . $bar_menu['url'];
                }
                $sub_menus[$k] = $bar_menu;
            }

            $all_menu['bar_menu'][$key] = $sub_menus;
        }

        return $all_menu;
    }

    /**
     * 获取所有的导航菜单.
     * @return array
     */
    private static function getLeftMenu()
    {
        return [
            'member'    =>  [
                'url'   =>  'merchant/user/index/index',
                'title' =>  '会员管理',
                'icon'  =>  'icon-yonghuguanli',
            ],
            // 用户管理.
            'user'  =>  [
                'url'   =>  'staff/index',
                'title' =>  '员工管理',
                'icon'  =>  'icon-kefu'
            ],
            'message'   =>  [
                'title' =>  '留言管理',
                'url'   =>  'merchant/message/message/index',
                'icon'  =>  'icon-liaotian',
            ],
            // 风格管理
            'style'     =>  [
                'url'   =>  'merchant/style/index/index',
                'title' =>  '风格管理',
                'icon'  =>  'icon-fengge'
            ],
            // 黑名单管理
            'blacklist' =>  [
                'url'   =>  'merchant/black/index/index',
                'title' =>  '黑名单管理',
                'icon'  =>  'icon-heimingdan'
            ],
            // 全局设置
            'settings'  =>  [
                'url'   =>  'merchant/overall/index/index',
                'title' =>  '全局设置',
                'icon'  =>  'icon-quanjushezhi',
            ],
        ];
    }

    /**
     * 获取所有的菜单项.
     * @return array
     */
    private static function getBarMenu()
    {
        return [
            'member'    =>  [
                'member'=>  [
                    'title' =>  '会员管理',
                    'url'   =>  'merchant/user/index/index',
                ],
            ],
            'settings'  =>  [
                'common_words'  =>  [
                    'title' =>  '常用语管理',
                    'url'   =>  'merchant/overall/index/index'
                ],
                'company'   =>  [
                    'title' =>  '企业设置',
                    'url'   =>  'company/index'
                ],
                'code'      =>  [
                    'title' =>  '客服代码',
                    'url'   =>  'merchant/overall/code/index',
                ],
            ],
            'blacklist' =>  [
                'blacklist' =>  [
                    'title' =>  '黑名单管理',
                    'url'   =>  'merchant/black/index/index'
                ]
            ],
            'style' =>  [
                'style' =>  [
                    'title' =>  '风格管理',
                    'url'   =>  'merchant/style/index/index'
                ],
                'setting'   =>  [
                    'title' =>  '风格设置',
                    'url'   =>  'merchant/style/setting/index'
                ],
                'reception' =>  [
                    'title' =>  '接待规则',
                    'url'   =>  'merchant/style/reception/index'
                ],
            ],
            'user'  =>  [
                'sub_user'  =>  [
                    'title' =>  '员工管理',
                    'url'   =>  'staff/index'
                ],
                'department'=>  [
                    'title' =>  '部门管理',
                    'url'   =>  'department/index'
                ],
                'role'      =>  [
                    'title' =>  '角色管理',
                    'url'   =>  'role/index'
                ],
                'action'    =>  [
                    'title' =>  '权限管理',
                    'url'   =>  'action/index',
                ],
                'staff_log' =>  [
                    'title' =>  '客服日志',
                    'url'   =>  'log/index'
                ],
            ],
            'message'   =>  [
                'track' =>  [
                    'title' =>  '聊天记录',
                    'url'   =>  'merchant/message/message/index',
                ],
                'leave' =>  [
                    'title' =>  '留言板管理',
                    'url'   =>  'merchant/message/leave/index'
                ]
            ],
        ];
    }

    /**
     * 后台菜单信息.
     * @return array
     */
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
                    [ "title" => "商户列表","url" => "/merchant/index" ],
                ]
            ],
            "setting"  => [
                "title" => "系统配置",
                "icon"  => "cogs",
                "sub"   =>  [
                    ["title" => "WS配置", "url" => "/setting/ws"],
                ],
            ],

        ];
        return $menus;
    }
}
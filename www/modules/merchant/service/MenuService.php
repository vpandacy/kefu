<?php
namespace www\modules\merchant\service;

use common\services\BaseService;

class MenuService extends BaseService
{
    /**
     * 获取所有的菜单.
     * @param array $urls
     * @param bool $is_root
     * @return array
     */
    public static function getAllMenu($urls, $is_root = false)
    {
        $all_menu = [
            'left_menu' =>  self::getLeftMenu(),
            'bar_menu'  =>  self::getBarMenu()
        ];

        if($is_root) {
            return $all_menu;
        }

        // 开始过滤菜单.
        foreach($all_menu['left_menu'] as $key=>$action) {
            if(!in_array($action['url'], $urls)) {
                unset($all_menu['left_menu'][$key]);
            }
        }

        foreach($all_menu['bar_menu'] as $key=>$sub_menus) {
            foreach($sub_menus as $k => $bar_menu) {
                if(!in_array($bar_menu['url'], $urls)) {
                    unset($sub_menus[$k]);
                }
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
            // 用户管理.
            'user'  =>  [
                'url'   =>  'merchant/staff/index/index',
                'title' =>  '用户管理',
                'icon'  =>  'icon-yonghuguanli'
            ],
            // 聊天管理
            'chat'  =>  [
                'url'   =>  'merchant/chat/index/index',
                'title' =>  '聊天管理',
                'icon'  =>  'icon-liaotian'
            ],
            // 全局设置
            'settings'  =>  [
                'url'   =>  'merchant/overall/index/index',
                'title' =>  '全局设置',
                'icon'  =>  'icon-quanjushezhi',
            ],
            // 黑名单管理
            'blacklist' =>  [
                'url'   =>  'merchant/black/index/index',
                'title' =>  '黑名单管理',
                'icon'  =>  'icon-heimingdan'
            ],
            // 风格管理
            'style'     =>  [
                'url'   =>  'merchant/style/index/index',
                'title' =>  '风格管理',
                'icon'  =>  'icon-fengge'
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
            'user'  =>  [
                'sub_user'  =>  [
                    'title' =>  '子帐号管理',
                    'url'   =>  'merchant/staff/index/index'
                ],
                'department'=>  [
                    'title' =>  '部门管理',
                    'url'   =>  'merchant/staff/department/index'
                ],
                'role'      =>  [
                    'title' =>  '角色管理',
                    'url'   =>  'merchant/staff/role/index'
                ],
                'action'    =>  [
                    'title' =>  '权限管理',
                    'url'   =>  'merchant/staff/action/index',
                ],
            ],
            'chat'  =>  [
                'chat'  =>  [
                    'title' =>  '聊天管理',
                    'url'   =>  'merchant/chat/index/index'
                ],
                'download'  =>  [
                    'title' =>  '下载记录',
                    'url'   =>  'merchant/chat/download/index',
                ],
            ],
            'settings'  =>  [
                'common_words'  =>  [
                    'title' =>  '常用语管理',
                    'url'   =>  'merchant/overall/index/index'
                ],
                'clueauto'  =>  [
                    'title' =>  '线索自动采集',
                    'url'   =>  'merchant/overall/clueauto/index',
                ],
                'breakauto' =>  [
                    'title' =>  '自动断开',
                    'url'   =>  'merchant/overall/breakauto/index'
                ],
                'company'   =>  [
                    'title' =>  '企业设置',
                    'url'   =>  'merchant/overall/company/index'
                ],
                'offline'   =>  [
                    'title' =>  '离线表单管理',
                    'url'   =>  'merchant/overall/offline/index'
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
                'computer'  =>  [
                    'title' =>  'PC设置',
                    'url'   =>  'merchant/style/computer/index'
                ],
                'mobile'    =>  [
                    'title' =>  '移动端设计',
                    'url'   =>  'merchant/style/mobile/index'
                ],
                'news'      =>  [
                    'title' =>  '自动消息',
                    'url'   =>  'merchant/style/newsauto/index',
                ],
                'reception' =>  [
                    'title' =>  '接待规则',
                    'url'   =>  'merchant/style/reception/index'
                ],
                'video'     =>  [
                    'title' =>  '视频',
                    'url'   =>  'merchant/style/video/index'
                ],
            ],
        ];
    }
}
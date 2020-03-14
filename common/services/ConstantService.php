<?php
namespace common\services;

class ConstantService extends BaseService
{
    public static $default_date = "1970-01-01";
    public static $default_datetime = "1970-01-01 00:00:00";
    public static $default_sys_err = "系统繁忙，请稍后再试~~";

    public static $common_status_mapping = [
        1 => "正常",
        0 => "已删除"
    ];

    public static $common_status_mapping2 = [
        1 => "可用",
        0 => "禁用"
    ];

    public static $common_status_map3 = [
        1 => "已处理",
        0 => "未处理"
    ];

    //　接口请求失败.
    public static $response_code_fail = -1;

    // 接口请求成功.
    public static $response_code_success = 200;

    // 帐号正常.
    public static $default_status_true = 1;

    // 帐号异常.
    public static $default_status_false = 0;

    public static $default_status_neg_1 = -1;
    public static $default_status_neg_99 = -99;
    /**
     * 应用关系对应.
     * @var array
     */
    public static $app_mapping = [
        1   =>  'www',
        2   =>  'admin',
    ];

    public static $app_mapping_str = [
        'www'   =>  '商户平台',
        'admin' =>  'Admin管理后台'
    ];

    // 定义一些常量ID.
    public static $merchant_app_id = 1;
    public static $admin_app_id = 2;

    public static $CS_APP = "cs";
    /**
     * 默认头像.
     * @var string
     */
    public static $default_avatar = 'default_avatar.png';


    //------- 游客动作　start ------//
    // 游客初次连接动作.
    public static $chat_cmd_guest_in = 'guest_in';
    // 游客关闭聊天动作.
    public static $chat_cmd_guest_close = 'guest_close';
    // 客服分配成功.
    public static $chat_cmd_assign_kf = "assign_kf";
    // 已经分配给客服，但是客服的接待区已经满了.
    public static $chat_cmd_assign_kf_wait = "assign_kf_wait";
    // 客服更换.
    public static $chat_cmd_change_kf = "change_kf";
    // 游客连接成功.
    public static $chat_cmd_guest_connect = "guest_connect";
    // 游客欢迎消息.
    public static $chat_cmd_hello = "hello";
    // 游客消息.
    public static $chat_cmd_chat = 'chat';
    //------- 游客动作  end   ------//


    //------- 客服动作  start ------//
    // 游客已经进入等待区.
    public static $chat_cmd_guest_wait_connect = "guest_connect_wait";
    // 系统消息.
    public static $chat_cmd_system = "system";
    // 客服回复消息.
    public static $chat_cmd_reply = 'reply';
    // 强制关闭游客.
    public static $chat_cmd_close_guest = 'close_guest';
    // 客服登录成功.
    public static $chat_cmd_kf_in = 'kf_in';
    // 客服退出.
    public static $chat_cmd_kf_logout = 'kf_logout';
    // 检查客服是否存在.
    public static $chat_cmd_kf_health = 'health_check';
    //------- 客服动作  end ------//



    //------- 心跳检测动作 start -------//
    // 心跳响应动作.
    public static $chat_cmd_pong = 'pong';
    // 心跳检查动作.
    public static $chat_cmd_ping = 'ping';
    //------- 心跳检测动作 end   -------//

    // 终端
    public static $guest_source = [
        1   =>  'PC',
        2   =>  '手机H5',
        3   =>  '微信'
    ];

    public static $worker_types = [
        1   =>  '注册中心',
        2   =>  '网关',
        3   =>  '业务'
    ];

    /**
     * 来源媒体.
     * @var array
     */
    public static $referer_media_types = [
        0   =>  '直接访问',
        10  =>  '百度',
        11  =>  '360',
        12  =>  '搜狗',
        13  =>  '神马',
        14  =>  '今日头条',
        15  =>  'oppo',
        16  =>  'vivo',
        17  =>  '小米',
        18  =>  'WIFI',
        19  =>  '趣头条',
        20  =>  'UC',
        21  =>  '一点咨讯',
        22  =>  '快手',
        23  =>  '广点通',
        24  =>  '陌陌',
        25  =>  'WPS',
        26  =>  '趣看天下',
        27  =>  '知乎',
        28  =>  '爱奇艺',
    ];


}
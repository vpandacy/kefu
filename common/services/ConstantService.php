<?php
namespace common\services;

class ConstantService extends BaseService
{
    public static $default_date = "1970-01-01";
    public static $default_datetime = "1970-01-01 00:00:00";

    //　接口请求失败.
    public static $response_code_fail = -1;

    // 接口请求成功.
    public static $response_code_success = 200;

    // 帐号正常.
    public static $default_status_true = 1;

    // 帐号异常.
    public static $default_status_false = 0;

    public static $default_status_neg_1 = -1;
    /**
     * 应用关系对应.
     * @var array
     */
    public static $app_mapping = [
        1   =>  'www',
    ];

    // 定义一些常量ID.
    public static $merchant_app_id = 1;

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
    //　客服分配成功
    public static $chat_cmd_assign_kf = "assign_kf";
    // 游客连接成功
    public static $chat_cmd_guest_connect = "guest_connect";
    // 游客欢迎消息.
    public static $chat_cmd_hello = "hello";
    // 游客消息.
    public static $chat_cmd_chat = 'chat';
    //------- 游客动作  end   ------//


    //------- 客服动作  start ------//
    // 系统消息
    public static $chat_cmd_system = "system";
    // 客服回复消息.
    public static $chat_cmd_reply = 'reply';
    // 强制关闭游客.
    public static $chat_cmd_close_guest = 'close_guest';
    // 客服登录成功.
    public static $chat_cmd_kf_in = 'kf_in';
    //------- 客服动作  end ------//



    //------- 心跳检测动作 start -------//
    // 心跳响应动作.
    public static $chat_cmd_pong = 'pong';
    // 心跳检查动作.
    public static $chat_cmd_ping = 'ping';
    //------- 心跳检测动作 end   -------//

}
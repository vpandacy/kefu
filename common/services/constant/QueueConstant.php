<?php
namespace common\services\constant;

class QueueConstant
{
    // 游客的redis的队列组件名.对应的是Yii::$app->get('list_guest').详情请查看main-local.php list_guest
    public static $instance_quest = "list_guest";
    // 游客的redis队列名.   如果组件有前缀.在redis中则为 前缀+队列名.
    public static $queue_guest_chat = "guest_chat";

    // 客服的redis的队列组件名.对应的是Yii::$app->get('list_cs').详情请查看main-local.php list_cs
    public static $instance_cs = "list_cs";
    // 客服的redis队列名称.  如果组件有前缀.在redis中则为 前缀+队列名.
    public static $queue_cs_chat = "cs_chat";

    //专门做一个队列存储聊天信息，然后通过job存到数据库中
    public static $instance_chat_log = "list_chat_log";
    //对应的queue
    public static $queue_chat_log = "chat_log";

}
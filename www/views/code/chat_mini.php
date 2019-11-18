<?php

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>pc右下角聊天</title>
    <link href="/css/fonts/iconfont.css" rel="stylesheet">
    <link href="/css/www/code/chat_mini.css" rel="stylesheet">
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
</head>

<body>
<div id="online_kf">
    <div class="show-hide-min">
        <div class="min-onclick">
            <div><i class="iconfont icon-xiaoxi"></i> </div>
            <div><span>和我们在线交谈！</span>  </div>
            <div id="online_show" class="online_show"><i class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></i></div>
        </div>
    </div>
    <div id="show-hide" class="show-hide">
        <div id='pc-online'>
            <div class="online-header">
                <div class="header-left">
                    <img class="logo" src="/images/www/code/test.png">
                    <span>在线客服</span>
                </div>
                <div class="header-right">
                    <i class="iconfont icon-fenxiang"></i>
                    <i  class="iconfont icon-jiantou9 show-hide-max"></i>
                </div>
            </div>
            <div class="online-content">
                <div class="tip-div">
                <span class="content-tip">
                    <span>显示上次聊天记录</span>
                    <span>欢迎您的咨询，期待为您服务！</span>
                </span>
                </div>
                <div class="content-message">
                    <div class="message-img">
                        <img class="logo" src="/images/www/code/test.png">
                    </div>
                    <div class="message-info">
                        <div class="message-name-date"><span>楠楠</span><span class="date">10:57:56</span></div>
                        <div class="message-message">您好，请问您的电话或微信是多少呢？稍后把详细资料、优化政策、产品图册，利润分析等发到您手机上，以便您更好的了解！</div>
                    </div>
                </div>
            </div>
            <div class="online-submit">
                <div class="submit-top"></div>
                <div class="submit-bottom">
                    <div class="bottom-left">
                        <i class="iconfont icon-biaoqing"></i>
                        <i class="iconfont icon-jianqie"></i>
                        <i class="iconfont icon-wenjian"></i>
                        <i class="iconfont icon-yiwenshuoming"></i>
                        <i class="iconfont icon-xiazai"></i>
                        <i class="iconfont icon-xingbiao"></i>
                    </div>
                    <div>
                        <div class="submit-button">发送</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/www/code/chat_mini.js"></script>
</body>
</html>

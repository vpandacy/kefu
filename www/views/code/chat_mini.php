<?php
use \common\services\GlobalUrlService;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>pc右下角聊天</title>
    <link href="<?=GlobalUrlService::buildUcStaticUrl("/css/component/iconfont/iconfont.css");?>" rel="stylesheet">
    <link href="<?=GlobalUrlService::buildWwwStaticUrl("/css/www/code/chat_mini.css");?>" rel="stylesheet">
    <link href="<?=GlobalUrlService::buildWwwStaticUrl("/css/www/code/tools.css");?>" rel="stylesheet" />
    <link href="<?=GlobalUrlService::buildWwwStaticUrl("/css/www/code/emojibg.css");?>" rel="stylesheet" />

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
                    <span class="iconfont icon-jiazaizhong" style="display: none;"></span>
                    <span class="line">显示上次聊天记录</span>
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

                <div class="content-message message-my">
                    <div class="message-info">
                        <div class="message-name-date name-date-my"><span class="date">10:57:56</span><span class="message-name">我</span></div>
                        <div class="message-message message-message-my">您好，请问您的电话或微信是多少呢？稍后把详细资料、优化政策、产品图册，利润分析等发到您手机上，以便您更好的了解！</div>
                    </div>
                </div>
            </div>
            <div class="online-submit">
                <div class="submit-top" id="content" contenteditable="true"></div>
                <div class="submit-bottom">
                    <div class="bottom-left">
                        <i class="iconfont icon-biaoqing" id="openFace"></i>
                        <i class="iconfont icon-jianqie" onclick="StartCapture()"></i>
                        <i class="iconfont icon-wenjian" ></i>
                        <input type="file" id="inputFile" onchange="inputFlie.changeFile()" style="display: none">
                        <i class="iconfont icon-yiwenshuoming"></i>
                        <i class="iconfont icon-xiazai"></i>
                        <i class="iconfont icon-xingbiao"></i>
                    </div>
                    <div>
                        <div class="submit-button">发送</div>
                    </div>
                </div>
                <div class="online-author">
                    <a>服务状态:已连接</a>
                    <a>好商汇提供软件支持</a></div>
            </div>
            <div class="faceDivBox" style="display:none;">
                <div class="faceDiv">
                    <section class="emoji-box"></section>
                </div>
                <a class="closeFaceBox" href="javascript:void(0)">×</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=GlobalUrlService::buildWwwStaticUrl("/js/www/code/jquery.min.js");?>"></script>
<script type="text/javascript" src="<?=GlobalUrlService::buildWwwStaticUrl("/js/www/code/jquery.md5.js");?>"></script>
<script type="text/javascript" src="<?=GlobalUrlService::buildWwwStaticUrl("/js/www/code/jquery.json-2.3.min.js?v=20150926");?>"></script>
<script type="text/javascript" src="<?=GlobalUrlService::buildWwwStaticUrl("/js/www/code/niuniucapture.js?v=20171108");?>"></script>
<script type="text/javascript" src="<?=GlobalUrlService::buildWwwStaticUrl("/js/www/code/capturewrapper.js?v=20171108");?>"></script>

<script type="text/javascript" src="<?=GlobalUrlService::buildWwwStaticUrl("/js/www/code/emojisort.js");?>"></script>
<script type="text/javascript" src="<?=GlobalUrlService::buildWwwStaticUrl("/js/www/code/emoji.js");?>"></script>
<script src="<?=GlobalUrlService::buildWwwStaticUrl("/js/www/code/chat_mini.js");?>"></script>

<script>
    $(document).ready(function(){
        /**
         * 表情
         * */
        sdEditorEmoj.Init(emojiconfig);
        sdEditorEmoj.setEmoji({type:'div',id:"content"});

        /**
         * 截图初始化
         */
        $().ready(function(){
            $('#moreparams').hide();

            $('#captureselectSize').click( function(){
                var autoFlag = $("#captureselectSize").attr("checked")=="checked" ? 1 : 0;
                if(autoFlag == 1){
                    $('#moreparams').show();
                }
                else{
                    $('#moreparams').hide();
                }
            });
            $('#getimagefromclipboard').click( function(){
                $('#posdetail').hide();
            });
            $('#showprewindow').click( function(){
                $('#posdetail').hide();
            });
            $('#fullscreen').click( function(){
                $('#posdetail').hide();
            });
            $('#specificarea').click( function(){
                $('#posdetail').show();
            });

            $('#showprewindow').click();
            $('#autoupload').click();
            $('#btnUpload').hide();
            Init();
        })
    })
</script>
</body>
</html>

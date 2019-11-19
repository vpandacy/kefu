<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>pc页聊天</title>
    <script src="/js/www/code/jquery.min.js"></script>
    <link href="/css/fonts/iconfont.css" rel="stylesheet">
    <link href="/css/www/code/online.css" rel="stylesheet">
    <link href="/css/www/code/tools.css" rel="stylesheet" />
    <link href="/css/www/code/emojibg.css" rel="stylesheet" />
    <script language="javascript" src="/js/www/code/jquery.md5.js"></script>
    <script language="javascript" src="/js/www/code/jquery.json-2.3.min.js?v=20150926"></script>
    <script language="javascript" src="/js/www/code/niuniucapture.js?v=20171108"></script>
    <script language="javascript" src="/js/www/code/capturewrapper.js?v=20171108"></script>
</head>

<body>
    <div id='online'>
        <div class="online-header">
            <img class="logo" src="/images/www/code/test.png">
            <div class="info">
                <span class="title">金牌客服</span>
                <span class="tip">为您在线解答售前(5*8)/售后咨询(7*24)服务</span>
            </div>
        </div>
        <div class="online-info">
            <div class="info-message">
                <div class="message">
                    <div class="tip-div">
                        <span class="message-tip">欢迎您的咨询，期待为您服务！</span>
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
                <div class="submit">
                    <div class="top">
                        <i class="iconfont icon-biaoqing" id="openFace"></i>
                        <i class="iconfont icon-jianqie" onclick="StartCapture()"></i>
                        <i class="iconfont icon-wenjian" ></i>
                        <input type="file" id="inputFile" onchange="inputFlie.changeFile()" style="display: none">
                        <i class="iconfont icon-yiwenshuoming"></i>
                        <i class="iconfont icon-xingbiao"></i>
                    </div>
                    <div class="print" id="content" contenteditable="true" ></div>
                    <div class="bottom">
                    <div class="submit-button">发送</div>
                </div>
                </div>
                <div class="faceDivBox" style="display:none;width: 500px;height: 150px;bottom: 155px;">
                    <div class="faceDiv">
                        <section class="emoji-box"></section>
                    </div>
                    <a class="closeFaceBox" href="javascript:void(0)">×</a>
                </div>
            </div>
            <div class="online-right">
                <div class="right-tab">
                    <div class="tab-one">关于我们</div>
                    <div class="tab-two">客服名片</div>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/www/code/chat_mini.js"></script>
    <script type="text/javascript" src="/js/www/code/emojisort.js"></script>
    <script type="text/javascript" src="/js/www/code/emoji.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            /**
             * 表情
             * */
            sdEditorEmoj.Init(emojiconfig);
            sdEditorEmoj.setEmoji({type: 'div', id: "content"});
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

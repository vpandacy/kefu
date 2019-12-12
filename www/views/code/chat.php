<?php
use common\services\GlobalUrlService;
use www\assets\AppAsset;
use common\components\helper\StaticPluginHelper;

StaticPluginHelper::setDepend(AppAsset::className());

// 这种引入还是一般.
StaticPluginHelper::includeCssPlugins([
    GlobalUrlService::buildUcStaticUrl('/css/component/iconfont/iconfont.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/chat.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/tools.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/emojibg.css'),
]);

StaticPluginHelper::includeJsPlugins([
    GlobalUrlService::buildStaticUrl('/socket/swfobject.js'),
    GlobalUrlService::buildStaticUrl('/socket/web_socket.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/jquery.min.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/jquery.md5.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/jquery.json-2.3.min.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/niuniucapture.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/capturewrapper.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/emoji.min.js'),
    // 这里先分开业务.后期在合并js.
    GlobalUrlService::buildKFStaticUrl('/js/www/code/chat.js'), // 主要实现业务逻辑.
    GlobalUrlService::buildKFStaticUrl('/js/www/code/core.js'), // 核心.比方说动画.上传动作.
]);

?>
<script> WEB_SOCKET_SWF_LOCATION = '<?=GlobalUrlService::buildStaticUrl('/socket/WebSocketMain.swf')?>'; </script>

<div id="online_kf" data-sn="<?=$merchant['sn']?>" data-code="<?=$code?>" data-uuid="<?=$uuid?>">
    <div class="show-hide-min" style="display: block">
        <div class="min-onclick">
            <div><i class="iconfont icon-xiaoxi"></i></div>
            <div><span>和我们在线交谈！</span></div>
            <div id="online_show" class="online_show">
                <i class="iconfonticon-changyongtubiao-xianxingdaochu-zhuanqu-"></i>
            </div>
        </div>
    </div>
    <div id="show-hide" class="show-hide" style="display: none">
        <div id='pc-online'>
            <div class="online-cover dis_none"></div>
            <div class="online-header">
                <div class="header-left">
                    <img class="logo" src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$merchant['logo'])?>">
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
                    <span></span>
                </span>
                </div>
                <div class="content-message">
                    <div class="message-img">
                        <img class="logo" src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$merchant['logo'])?>">
                    </div>
                    <div class="message-info">
                        <div class="message-name-date"><span>客服</span><span class="date"><?=date('Y-m-d H:i:s')?></span></div>
                        <span class="message-message"><?=!$setting ? '您好,欢迎使用好商汇客服系统' : $setting['greetings']?></span>
                    </div>
                </div>
            </div>
            <div class="online-submit">
                <div class="submit-top" id="content" contenteditable="true"></div>
                <div class="submit-bottom">
                    <div class="bottom-left">
                        <i class="iconfont icon-biaoqing" id="openFace"></i>
<!--                        onclick="StartCapture()"-->
                        <i class="iconfont icon-jianqie" ></i>
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

    <div class="capture-dialog dis_none">
        <div class="modal-content">
            <div class="modal-header">
                <span class="iconfont icon-guanbi" aria-hidden="true" type="button" data-dismiss="modal"></span>
                <h4 class="modal-title">截图说明</h4>
            </div>
            <input style="left: 0px; top: 0px; position: absolute; z-index: -1;" />
            <div class="modal-body">
                <div class="capture-outer" style="width: 320px;">
                    <p class="captTips">提示：建议使用微信或QQ的截图功能进行截图，然后直接粘贴到对话框即可。</p>
                    <p class="h5">您可按照以下步骤下载截图插件：</p>
                    <ol>
                        <li><h6>下载地址</h6>
                            <div class="capture-text">
                                Windows系统下载qq截图插件
                                <a href="https://img.sobot.com/tools/%E6%88%AA%E5%9B%BE%E5%B7%A5%E5%85%B7-win.exe" target="_blank">点击此处</a>
                            </div>
                            <div class="capture-text">
                                Mac系统下载qq截图插件
                                <a href="https://img.sobot.com/tools/%E6%88%AA%E5%9B%BE%E5%B7%A5%E5%85%B7-mac.dmg" target="_blank">点击此处</a>
                            </div></li>
                        <li><h6>完成下载后，请找到程序所在文件夹，然后按照下面链接的方法设置截图快捷键</h6>
                            <div class="capture-text">
                                Windows系统下
                                <a href="https://jingyan.baidu.com/article/2fb0ba408a1e1300f2ec5f03.html" target="_blank">安装设置方法</a>
                            </div>
                            <div class="capture-text">
                                Mac系统下
                                <a href="https://jingyan.baidu.com/article/3d69c5514e44a3f0ce02d751.html" target="_blank">安装设置方法</a>
                            </div></li>
                        <li><h6>完成上述步骤后，即可使用。</h6></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden-wrapper">
    <input type="hidden" name="host" value="<?=$host?>">
</div>
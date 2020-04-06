<?php
use common\services\GlobalUrlService;
use www\assets\AppAsset;
use common\components\helper\StaticPluginHelper;

StaticPluginHelper::setDepend(AppAsset::className());
StaticPluginHelper::socketPlugin();
// css
StaticPluginHelper::includeCssPlugins([
    GlobalUrlService::buildStaticUrl('/chat/emoji/emojibg.css'),
    GlobalUrlService::buildStaticUrl('/jqMsg/message.css'),
    GlobalUrlService::buildUcStaticUrl('/css/component/iconfont/iconfont.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/mobile.css'),
]);
// js
StaticPluginHelper::includeJsPlugins([
    GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery-3.2.1.min.js'),
    GlobalUrlService::buildStaticUrl('/jqMsg/message.min.js'),
    GlobalUrlService::buildStaticUrl('/chat/emoji/emoji.js'),
//    GlobalUrlService::buildStaticUrl('/vConsole/vconsole.min.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/socket.common.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/mobile.js'),
    GlobalUrlService::buildKFStaticUrl('/js/component/storage.js'),
    //监控请求JS
    GlobalUrlService::buildKFUrl('/error/log'),
]);
?>
<script>
    WEB_SOCKET_SWF_LOCATION = '<?=GlobalUrlService::buildStaticUrl('/socket/WebSocketMain.swf')?>';
</script>
<div id='wapOnline'>
    <div class="wapOnline-zheyan dis_none"></div>
    <div class="iconfont icon-zaixianzixun" style="color: rgb(58, 148, 254)"></div>
    <div class="waponline-max dis_none">
        <div class="top">
            <i class="iconfont icon-zuojiantou"></i>
            <div class="title">
                <span>在线咨询</span>
<!--                <i class="iconfont icon-jiantou9"></i>-->
            </div>
            <div></div>
        </div>
        <div class="message">
            <div class="tip tip-div">
                <div class="show-message">
                    <span class="iconfont icon-jiazaizhong" style="display: none;"></span>
                    <span class="line">显示上次聊天记录</span>
                </div>
            </div>
            <div class="content-message">
                <div class="message-img">
                    <img class="logo" src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$merchant_info['logo'])?>">
                </div>
                <div class="message-info">
                    <div class="message-name-date"><span>客服</span><span class="date"><?=date('H:i:s')?></span></div>
                    <div class="message-message">
                        <?=$js_params['greetings']?>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom">
            <div class="icon">
                <i class="iconfont icon-biaoqing" id="openFace"></i>
            </div>
            <div class="mobile-text-disflex">
                <div type="text" contenteditable="true" id="content" name="message" placeholder="请输入...">
                </div>
                <div>
                    <div class="submit-button">发送</div>
                </div>
            </div>
            <div class="chat-close dis_none">
                当前对话已结束，您可以开始 <span class="online_new_message">新对话</span> 或 <span class="online_from_message">留言</span>
            </div>
        </div>
        <div class="online-author">
            <a>服务状态:<span class="ws_flag"></span></a>
            <a>商通提供软件支持</a></div>
        <div class="overflow-message dis_none">
            <p>当前客服接待能力已达上限，请耐心等候！</p>
            <div class="operation">
                <div>当前等待人数：<span class="num">0</span></div>
                <div class="leave-message">
                    <span>转留言</span>
                </div>
            </div>
        </div>

        <div class="faceDivBox" style="display:none;height: 137px; bottom: 4.5rem;width: 88%;overflow: auto; position: absolute;background: white;">
            <div class="faceDiv">
                <section class="emoji-box"></section>
            </div>
<!--            <a class="closeFaceBox" href="javascript:void(0)">×</a>-->
        </div>

        <div id="online-from" style="display: none;">
            <div>您好, 我们的服务时间：8:00-24:00，现在客服不在线，请留言. 如果没有留下您的联系方式，客服将无法和您联系！</div>
            <div class="from-list">
                <div class="from-title"><span >*</span><span>姓名</span></div>
                <input name="name">
            </div>
            <div class="from-list">
                <div class="from-title"><span>*</span><span>手机号</span></div>
                <input name="mobile">
            </div>
            <div class="from-list">
                <div class="from-title"><span></span><span>微信号</span></div>
                <input name="wechat">
            </div>
            <div class="from-list">
                <div class="from-title"><span></span><span>留言内容</span></div>
                <textarea name="message"></textarea>
            </div>
            <div class="from-list">
                <div class="from-button-message">提交留言</div>
            </div>
        </div>
    </div>
</div>
<div class="hidden_wrapper">
    <input type="hidden" name="params" value='<?=json_encode($js_params);?>'>
</div>
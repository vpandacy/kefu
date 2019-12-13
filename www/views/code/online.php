<?php
use common\components\helper\StaticPluginHelper;
use common\services\GlobalUrlService;
use www\assets\AppAsset;

StaticPluginHelper::setDepend(AppAsset::className());

StaticPluginHelper::socketPlugin();

// 这种引入还是一般.
StaticPluginHelper::includeCssPlugins([
    GlobalUrlService::buildUcStaticUrl('/css/component/iconfont/iconfont.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/online.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/tools.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/emojibg.css'),
]);

StaticPluginHelper::includeJsPlugins([
    GlobalUrlService::buildKFStaticUrl('/js/www/code/jquery.min.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/jquery.md5.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/jquery.json-2.3.min.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/niuniucapture.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/capturewrapper.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/emoji.min.js'),
    GlobalUrlService::buildKFStaticUrl('/js/component/storage.js'),
    // 自己的js.
    GlobalUrlService::buildKFStaticUrl('/js/www/code/online.js')
]);
?>
<script> WEB_SOCKET_SWF_LOCATION = '<?=GlobalUrlService::buildStaticUrl('/socket/WebSocketMain.swf')?>'; </script>
<div id='online'>
    <div class="online-header">
        <img class="logo" src="<?=GlobalUrlService::buildKFStaticUrl('/images/www/code/test.png')?>">
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
                <div class="content-message online-my-message">
                    <div class="message-info">
                        <div class="message-name-date"><span class="date">10:57:56</span><span>我</span></div>
                        <div class="message-message">您好，请问您的电话或微信是多少呢？稍后把详细资料、优化政策、产品图册，利润分析等发到您手机上，以便您更好的了解！</div>
                    </div>
                </div>
            </div>
            <div class="submit">
                <div class="top">
                    <i class="iconfont icon-biaoqing" id="openFace"></i>
                    <i class="iconfont icon-yiwenshuoming"></i>
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
            <div class="right-tab-info">123</div>
            <div class="right-guanggao">廣告位（招商中）</div>
        </div>
    </div>
</div>
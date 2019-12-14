<?php
use common\components\helper\StaticPluginHelper;
use common\services\GlobalUrlService;
use www\assets\AppAsset;

StaticPluginHelper::setDepend(AppAsset::className());

StaticPluginHelper::socketPlugin();

// 这种引入还是一般.
StaticPluginHelper::includeCssPlugins([
    GlobalUrlService::buildStaticUrl('/chat/emoji/emojibg.css'),
    GlobalUrlService::buildUcStaticUrl('/css/component/iconfont/iconfont.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/online.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/tools.css'),
]);

StaticPluginHelper::includeJsPlugins([
    GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery-3.2.1.min.js'),
    GlobalUrlService::buildStaticUrl('/chat/jquery.md5.js'),
    GlobalUrlService::buildStaticUrl('/chat/jquery.json-2.3.min.js'),
    GlobalUrlService::buildStaticUrl('/chat/emoji/emoji.js'),
    GlobalUrlService::buildKFStaticUrl('/chat/capturewrapper.js'),
    GlobalUrlService::buildKFStaticUrl('/js/component/storage.js'),
    // 公共的socket聊天JS
    GlobalUrlService::buildKFStaticUrl('/js/www/code/socket.common.js'),
    // 自己的js.
    GlobalUrlService::buildKFStaticUrl('/js/www/code/online.js')
]);
?>
<script> WEB_SOCKET_SWF_LOCATION = '<?=GlobalUrlService::buildStaticUrl('/socket/WebSocketMain.swf')?>'; </script>
<div id='online'>
    <div class="online-header">
        <img class="logo" src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$merchant['logo'])?>">
        <div class="info">
            <span class="title">金牌客服</span>
            <span class="tip">为您在线解答售前(5*8)/售后咨询(7*24)服务</span>
        </div>
    </div>
    <div class="online-info">
        <div class="info-message">
            <div class="message">
                <div class="tip-div">
                    <span class="iconfont icon-jiazaizhong" style="display: none;"></span>
                    <span class="message-tip"><?=$setting['greetings'] ?? '欢迎您的咨询，期待为您服务！'?></span>
                </div>
            </div>
            <div class="submit">
                <div class="top">
                    <i class="iconfont icon-biaoqing" id="openFace"></i>
<!--                    <i class="iconfont icon-yiwenshuoming"></i>-->
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
                <div class="tab-one">
                    公司简介
                </div>
<!--                <div class="tab-one right-tab-active">客服名片</div>-->
            </div>
            <div class="right-tab-info">
                <div style="padding: 20px;">
                    <?=$merchant['desc']?>
                </div>
            </div>
<!--            <div class="right-guanggao">-->
<!--                <div class="online-advertisement">-->
<!--                    <div></div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</div>
<div class="hidden_wrapper">
    <input type="hidden" name="params" value='<?=json_encode($js_params);?>'>
</div>
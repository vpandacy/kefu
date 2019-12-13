<?php
use common\services\GlobalUrlService;
use www\assets\AppAsset;
use common\components\helper\StaticPluginHelper;

StaticPluginHelper::setDepend(AppAsset::className());
StaticPluginHelper::socketPlugin();
// css
StaticPluginHelper::includeCssPlugins([
    GlobalUrlService::buildUcStaticUrl('/css/component/iconfont/iconfont.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/mobile.css')
]);
// js
StaticPluginHelper::includeJsPlugins([
    GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery-3.2.1.min.js'),
    GlobalUrlService::buildKFStaticUrl('/js/www/code/mobile.js'),
    GlobalUrlService::buildKFStaticUrl('/js/component/storage.js')
]);
?>
<script> WEB_SOCKET_SWF_LOCATION = '<?=GlobalUrlService::buildStaticUrl('/socket/WebSocketMain.swf')?>'; </script>
<div id='wapOnline' data-sn="<?=$merchant['sn']?>" data-code="<?=$code?>" data-uuid="<?=$uuid?>">
    <div class="iconfont icon-zaixianzixun" style="color: rgb(58, 148, 254)"></div>
    <div class="waponline-max dis_none">
        <div class="top">
            <i class="iconfont icon-zuojiantou"></i>
            <div class="title">
                <span>在线咨询</span>
                <i class="iconfont icon-jiantou9"></i>
            </div>
            <div></div>
        </div>
        <div class="content">
            <div class="tip">
                <span>欢迎您的咨询，期待为您服务！</span>
            </div>
            <div class="date">
                <span>15:34</span>
            </div>
            <div class="content-message">
                <div class="message-img">
                    <img class="logo" src="./asstes/test.png">
                </div>
                <div class="message-info">
                    <div class="message-message">您好，请问您的电话或微信是多少呢？稍后把详细资料、优化政策、产品图册，利润分析等发到您手机上，以便您更好的了解！</div>
                </div>
            </div>
        </div>
        <div class="bottom">
            <div class="icon">
                <i class="iconfont icon-biaoqing"></i>
            </div>
            <div>
                <input type="text" name="message" placeholder="请输入...">
            </div>
            <div>
                <input type="hidden" name="host" value="<?=$host?>">
                <span class="submit">发送</span>
            </div>
        </div>
    </div>
</div>

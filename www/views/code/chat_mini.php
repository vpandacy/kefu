<?php
use common\services\GlobalUrlService;
use www\assets\AppAsset;
use common\components\helper\StaticAssetsHelper;

StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildUcStaticUrl('/css/component/iconfont/iconfont.css'), AppAsset::className());
StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildWwwStaticUrl('/css/www/code/chat_mini.css'), AppAsset::className());
StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildWwwStaticUrl('/css/www/code/tools.css'), AppAsset::className());
StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildWwwStaticUrl('/css/www/code/emojibg.css'), AppAsset::className());

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/www/code/jquery.min.js'), AppAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/www/code/jquery.md5.js'), AppAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/www/code/jquery.json-2.3.min.js'), AppAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/www/code/niuniucapture.js'), AppAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/www/code/capturewrapper.js'), AppAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/www/code/emojisort.js'), AppAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/www/code/emoji.js'), AppAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/www/code/chat_mini.js'), AppAsset::className())

?>
<div id="online_kf" data-sn="<?=$merchant['sn']?>" data-code="<?=$code?>">
    <div class="show-hide-min" style="display: block">
        <div class="min-onclick">
            <div><i class="iconfont icon-xiaoxi"></i> </div>
            <div><span>和我们在线交谈！</span>  </div>
            <div id="online_show" class="online_show"><i class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></i></div>
        </div>
    </div>
    <div id="show-hide" class="show-hide" style="display: none">
        <div id='pc-online'>
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
                        <div class="message-message"><?=!$setting ? '您好,欢迎使用好商汇客服系统' : $setting['greetings']?></div>
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
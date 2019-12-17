<?php
use common\services\GlobalUrlService;
use www\assets\AppAsset;
use common\components\helper\StaticPluginHelper;

StaticPluginHelper::setDepend(AppAsset::className());

StaticPluginHelper::socketPlugin();

// 这种引入还是一般.
StaticPluginHelper::includeCssPlugins([
    GlobalUrlService::buildStaticUrl('/chat/emoji/emojibg.css'),
    GlobalUrlService::buildStaticUrl('/chat/emoji/tools.css'),
    GlobalUrlService::buildUcStaticUrl('/css/component/iconfont/iconfont.css'),
    GlobalUrlService::buildKFStaticUrl('/css/www/code/chat.css'),
]);

StaticPluginHelper::includeJsPlugins([
    GlobalUrlService::buildStaticUrl('/plugins/jquery/jquery-3.2.1.min.js'),
    GlobalUrlService::buildStaticUrl('/chat/jquery.md5.js'),
    GlobalUrlService::buildStaticUrl('/chat/jquery.json-2.3.min.js'),
    GlobalUrlService::buildStaticUrl('/chat/emoji/emoji.js'),
    GlobalUrlService::buildKFStaticUrl('/js/component/storage.js'),
    // 公共的socket聊天JS
    GlobalUrlService::buildKFStaticUrl('/js/www/code/socket.common.js'),
    // 这里先分开业务.后期在合并js.
    GlobalUrlService::buildKFStaticUrl('/js/www/code/core.js'), // 核心.比方说动画.上传动作.
]);

?>
<script> WEB_SOCKET_SWF_LOCATION = '<?=GlobalUrlService::buildStaticUrl('/socket/WebSocketMain.swf')?>'; </script>

<div id="online_kf">
    <div class="show-hide-min" style="display: block">
        <div class="min-onclick">
            <div><i class="iconfont icon-xiaoxi"></i></div>
            <div><span>和我们在线交谈！</span></div>
            <div id="online_show" class="online_show">
                <i class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-"></i>
            </div>
        </div>
    </div>
    <div id="show-hide" class="show-hide" style="display: none">
        <div id='pc-online'>
            <div class="online-cover dis_none"></div>
            <div class="online-header">
                <div class="header-left">
                    <img class="logo" src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$merchant_info['logo'])?>">
                    <span>在线客服</span>
                </div>
                <div class="header-right">
                    <i class="iconfont icon-_DYGYxinyemiandakai"></i>
                    <i  class="iconfont icon-jiantou9 show-hide-max"></i>
                </div>
            </div>
            <div class="message">
                <div class="tip-div">
                    <span class="content-tip">
                    <span class="iconfont icon-jiazaizhong" style="display: none;"></span>
                    <span class="line">显示上次聊天记录</span>
                    <span></span>
                </span>
                </div>
                <div class="content-message">
                    <div class="message-img">
                        <img class="logo" src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$merchant_info['logo'])?>">
                    </div>
                    <div class="message-info">
                        <div class="message-name-date"><span>客服</span><span class="date"><?=date('Y-m-d H:i:s')?></span></div>
                        <div class="message-message"><?=$js_params['greetings']?></div>
                    </div>
                </div>
            </div>
            <div class="online-submit">
                <div class="submit-top" id="content" contenteditable="true"></div>
                <div class="submit-bottom">
                    <div class="bottom-left">
                        <i class="iconfont icon-biaoqing" id="openFace"></i>
                        <i class="iconfont icon-yiwenshuoming"></i>
                    </div>
                    <div>
                        <div class="submit-button">发送</div>
                    </div>
                </div>
                <div class="online-author">
                    <a>服务状态:<span class="ws_flag"></span></a>
                    <a>好商汇提供软件支持</a>
                </div>
                <div class="chat-close dis_none">当前对话已结束，您可以开始
                    <span class="online_new_message">新对话</span>
                    或
                    <span class="online_from_message">留言</span></div>
            </div>
            <div class="faceDivBox" style="display:none;">
                <div class="faceDiv">
                    <section class="emoji-box"></section>
                </div>
                <a class="closeFaceBox" href="javascript:void(0)">×</a>
            </div>
        </div>
        <div id="online-from">
            <div>您好, 我们的服务时间：8:00-24:00，现在客服不在线，请留言. 如果没有留下您的联系方式，客服将无法和您联系！</div>
            <div class="from-list">
                <div class="from-title"><span ></span><span>姓名</span></div>
                <input name="name">
            </div>
            <div class="from-list">
                <div class="from-title"><span></span><span>手机号</span></div>
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
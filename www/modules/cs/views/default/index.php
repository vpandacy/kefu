<?php
use common\components\helper\StaticAssetsHelper;
use common\components\helper\StaticPluginHelper;
use common\services\GlobalUrlService;
use www\assets\CsAsset;

StaticPluginHelper::setDepend(CsAsset::className());
StaticPluginHelper::socketPlugin();

StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildStaticUrl('/chat/emoji/emojibg.css'),CsAsset::className());
StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildStaticUrl('/chat/emoji/tools.css'),CsAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/chat/emoji/emoji.js'), CsAsset::className());

// 注意  这里最后要整合在一起的.
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/default/contextmenu.js'), CsAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/default/page.js'), CsAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/default/socket.js'), CsAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/default/chat.js'), CsAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/default/index.js'), CsAsset::className());
?>
<script>
    WEB_SOCKET_SWF_LOCATION = '<?=GlobalUrlService::buildStaticUrl('/socket/WebSocketMain.swf')?>';
    online_users = Object.values(<?=json_encode($online_users)?>);
    offline_users= Object.values(<?=json_encode($offline_users)?>);
</script>
<div id='chatExe'>
    <!-- 右键菜单 -->
    <div id="menu" style="z-index: 1">
        <a data-event="edit">编辑</a>
        <a data-event="transfer">游客转让</a>
        <a data-event="close">关闭聊天</a>
        <a data-event="black">拉入黑名单</a>
    </div>

    <!-- 最右侧菜单栏-->
    <div class="exe-menu">
        <div class="menu">
            <img class="menu-head" src="<?=GlobalUrlService::buildKFStaticUrl("/images/merchant/logo.png");?>">
            <div class="menu-online"></div>
            <div class="menu-icon-top-bottom">
                <div class="menu-icon-top">
                    <i class="iconfont icon-xiaoxi icon icon-action fsize32"></i>
                </div>
                <div class="menu-icon-bottom">
                    <i class="iconfont icon-zaixian icon icon-action fsize32 exe-off-online" title="在线"></i>
                    <i class="iconfont icon-tuichu icon icon-action fsize32" title="退出"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 右侧聊天记录、聊天对象-->
    <div class="exe-keep">
        <div class="tab fg1" >
            <div class="tab-switch"  >
                <div class="tab-one">
                    <i class="iconfont icon-xiaoxi fsize25"></i>
<!--                    游客区-->
                </div>
                <div class="tab-one">
                    <i class="iconfont icon-dingshi fsize25"></i>
<!--                    等待区-->
                </div>
            </div>
            <div class="tab-content">
                <div class="content-one online">
                    <div class="tab-content-list content-no-message">
                        暂无消息
                    </div>
                </div>
                <div class="content-one offline" style="display: none;">
                    <div class="tab-content-list">
                        <div>
                            <i class="iconfont icon-shouji"></i>
                            <span>莆田4</span>
                        </div>
                        <div>
                            <span class="content-list-time">09:45</span>
                        </div>
                    </div>
                    <div class="tab-content-list">

                    </div>
                </div>
            </div>
        </div>
        <div class="keep-census">
            <a>对话人数:<span class="online">0</span> </a>
            <a>等待人数:<span class="wait">0</span></a>
        </div>
    </div>

    <!-- 聊天栏 -->
    <div class="flex1" style="display: flex;">
        <div class="exe-header">
            <div class="exe-header-info-left">
                <span>暂无</span>
                <span>暂无 (暂无)</span>
            </div>
            <div class="exe-header-info-right">
                <span><i class="iconfont icon-guanbi"></i></span>
            </div>
        </div>
        <div class="exe-content-info dflex">
            <div class="exe-content">
                <div class="exe-content-top">
                    <div class="content-top-info">
                    <!-- <div class="info keyword"><span>关键词：</span><span>-</span></div>-->
                        <div class="info land-url"><span>咨询界面：</span><span>-</span></div>
                    </div>
                    <div class="content-top-info">
                        <div class="info source"><span>终端：</span><span>-</span></div>
                        <div class="info referer-url"><span>来源：</span><span>-</span></div>
                    </div>
                </div>
                <div class="exe-content-history-load"></div>
                <div class="exe-content-history">
                    <div class="exe-content-history-load">
                        <div class="iconfont icon-jiazaizhong dis_none"></div>
                        <div class="history-look">查看更多消息</div>
                    </div>
                    <div class="exe-content-history-content"></div>
                </div>
                <div class="exe-content-sumbit" style="position: relative;">
                    <div>
                        <span>
                            <i class="iconfont icon-biaoqing" id="openFace"></i>
                        </span>
                    </div>

                    <div class="faceDivBox" style="display:none;height: 150px; bottom: 155px;max-width: 100%;">
                        <div class="faceDiv">
                            <section class="emoji-box"></section>
                        </div>
                        <a class="closeFaceBox" href="javascript:void(0)">×</a>
                    </div>

                    <div class="sumbit-input" id="content" contenteditable="true" ></div>
                    <div class="sumbit-bottom">
                        <div></div>
                        <div class="button">
                            <div class="sumbit">发送<i class="iconfont icon-anjianfengexian"></i><i class="iconfont icon-jiantou"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="exe-info">
                <div class="tab fGrow1 one">
                    <div class="tab-switch height10 height50" >
                        <div class="tab-one switch-action">访客信息</div>
                        <div class="tab-one">查看轨迹</div>
                    </div>
                    <div class="tab-content">
                        <div class="content-one">
                            <div>
                                姓名：<span class="name"></span>
                            </div>
                            <div>
                                电话：<span class="mobile"></span>
                            </div>
                            <div>
                                邮箱：<span class="email"></span>
                            </div>
                            <div>
                                QQ：<span class="qq"></span>
                            </div>
                            <div>
                                微信：<span class="wechat"></span>
                            </div>
                            <div class="dflex
">
                                <span>备注：</span>
                                <span class="desc">
                                </span>
                            </div>
                        </div>
                        <div class="content-one access-track" style="display: none;max-height: 380px;overflow-y: auto;">
                            <div style="text-align: center;color: rgb(179, 181, 185) !important;">暂无</div>
                        </div>
                    </div>
                </div>
                <div class="tab fGrow1 two">
                    <div class="tab-switch height10 height50" >
                        <div class="tab-one" ><a>常用语</a></div>
                    </div>
                    <div class="tab-content">
                        <div class="content-one">
                            <div class="words-content">
                                <?php foreach($words as $word):?>
                                    <div class="content-select">
                                        <i class="iconfont icon-wenjian"></i>
                                        <span><?=$word['words']?></span>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                        <div class="content-one" style="display: none;">
                            123
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hidden_wrapper">
    <input type="hidden" name="params" value='<?=json_encode($js_params);?>'>
</div>
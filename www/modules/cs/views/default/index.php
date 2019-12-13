<?php
use common\components\helper\StaticAssetsHelper;
use common\components\helper\StaticPluginHelper;
use common\services\GlobalUrlService;
use www\assets\CsAsset;

StaticPluginHelper::setDepend(CsAsset::className());
StaticPluginHelper::socketPlugin();

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/cs/client.js'), CsAsset::className());
?>
<script>WEB_SOCKET_SWF_LOCATION = '<?=GlobalUrlService::buildStaticUrl('/socket/WebSocketMain.swf')?>'</script>

<div class="form-wrapper" style="display: none">
    <input type="hidden" name="cs_sn" value="<?=$staff['sn']?>">
</div>
<div id='chatExe'>
    <!-- 右键菜单 -->
    <div id="menu" >
        <a data-event="edit">编辑</a>
        <a data-event="del">删除</a>
        <a data-event="black">拉入黑名单</a>
    </div>


    <!-- 最右侧菜单栏-->
    <div class="exe-menu">
        <div>
            <img class="menu-head" src="./asstes/test.png">
            <div class="menu-online"></div>
            <div class="menu-icon-top">
                <i class="iconfont icon-xiaoxi icon icon-action fsize32"></i>
            </div>
            <div class="menu-icon-top">
                <i class="iconfont icon-shoujidiannao icon fsize32"></i>
            </div>
        </div>
        <div class="menu-icon-bottom">
            <div class="menu-icon-top">
                <i class="iconfont icon-shezhi icon fsize22"></i>
            </div>
            <div class="menu-icon-top">
                <i class="iconfont icon-guanliyuan icon fsize22"></i>
            </div>
            <div class="menu-icon-top">
                <i class="iconfont icon-kuai icon fsize22"></i>
            </div>
            <div class="menu-icon-top">
                <i class="iconfont icon-fengshan icon fsize22"></i>
            </div>
            <div class="menu-icon-top">
                <i class="iconfont icon-gengduo icon fsize22"></i>
            </div>
        </div>
    </div>

    <!-- 右侧聊天记录、聊天对象-->
    <div class="exe-keep">
        <div class="tab fg1" >
            <div class="tab-switch"  >
                <div class="tab-one switch-action">
                    <i class="iconfont icon-xiaoxi fsize25"></i>
                </div>
                <div class="tab-one">
                    <i class="iconfont icon-dingshi fsize25"></i>
                </div>
            </div>
            <div class="tab-content">
                <div class="content-one online">
                    <div class="tab-content-list content-no-message">
                        暂无消息
                    </div>
                </div>
                <div class="content-one" style="display: none;">
                    <div oncontextmenu="tab.list(event)" class="tab-content-list">
                        <div>
                            <i class="iconfont icon-shouji"></i>
                            <span>莆田4</span>
                        </div>
                        <div>
                            <span class="content-list-time">09:45</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="keep-census">
            <a>对话:2</a>
            <a>等待:0</a>
            <a>访客:1329</a>
        </div>
    </div>

    <!-- 聊天栏 -->
    <div class="flex1">
        <div class="exe-header">
            <div class="exe-header-info-left">
                <span>11166754250007</span>
                <span>湖北省孝感市 [ 移动 ] (117.152.175.202)</span>
            </div>
            <div class="exe-header-info-right">
                <span>广点通c1</span>
                <span><i class="iconfont icon-anjianfengexian"></i></span>
                <span><i class="iconfont icon-kefu"></i></span>
                <span><i class="iconfont icon-icon_notice"></i></span>
                <span><i class="iconfont icon-zuixiaohua"></i></span>
                <span><i class="iconfont icon-zuidahua"></i></span>
                <span><i class="iconfont icon-guanbi"></i></span>
            </div>
        </div>
        <div class="exe-content-info dflex">
            <div class="exe-content">
                <div class="exe-content-top">
                    <div class="content-top-info">
                        <div class="info"><span>关键词：</span><span>-</span></div>
                        <div class="info"><span>咨询页面：</span><span>-</span></div>
                    </div>
                    <div class="content-top-info">
                        <div class="info"><span>来源：</span><span>-</span></div>
                        <div class="info"><span>渠道：</span><span>-</span></div>
                    </div>
                </div>
                <div class="exe-content-history">
                    <!-- 这里是提示信息 -->
<!--                    <div class="history-close">关闭页面</div>-->
                </div>
                <div class="exe-content-sumbit">
                    <div>
                        <span>
                            <i class="iconfont icon-ai247"></i>
                            <i class="iconfont icon-biaoqing"></i>
                            <i class="iconfont icon-tupian"></i>
                            <i class="iconfont icon-wenjian"></i>
                            <i class="iconfont icon-jietu"></i>
                            <i class="iconfont icon-biaoqing"></i>
                            <i class="iconfont icon-xingbiao"></i>
                            <i class="iconfont icon-fenxiang"></i>
                        </span>
                        <span>
                            <label>功能扩展</label>
                            <label>消息记录</label>
                        </span>
                    </div>
                    <div class="sumbit-input" contenteditable="true" ></div>
                    <div class="sumbit-bottom">
                        <div>
                            <input type="checkbox" />
                            消息预览
                        </div>
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
                        <div>+添加标签</div>
                        <div>姓名：</div>
                        <div>电话：</div>
                        <div>邮箱：</div>
                        <div>QQ：</div>
                        <div>微信：</div>
                        <div>备注：</div>
                        </div>
                        <div class="content-one" style="display: none;">
                            <div>+添加标签</div>
                            <div>姓名：</div>
                            <div>电话：</div>
                        </div>
                    </div>
                </div>
                <div class="tab fGrow1 two">
                    <div class="tab-switch height10 height50" >
                        <div class="tab-one switch-action" ><a>常用语</a></div>
                        <div class="tab-one"><a>常用文件</a></div>
                    </div>
                    <div class="tab-content">
                        <div class="content-one">
                        <input type="text" placeholder="关键词搜索">
                        <div class="content-select">
                            <i class="iconfont icon-wenjian"></i>
                            <span>默认私有词语</span>
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

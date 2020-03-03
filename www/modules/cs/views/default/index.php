<?php
use common\components\helper\StaticAssetsHelper;
use common\components\helper\StaticPluginHelper;
use common\services\GlobalUrlService;
use www\assets\CsAsset;

StaticPluginHelper::setDepend(CsAsset::className());
StaticPluginHelper::socketPlugin();

StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildStaticUrl('/chat/emoji/emojibg.css'),StaticPluginHelper::getDepend() );
StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildStaticUrl('/chat/emoji/tools.css'),StaticPluginHelper::getDepend());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/chat/emoji/emoji.js'), StaticPluginHelper::getDepend() );

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/clipboard/clipboard.js'), StaticPluginHelper::getDepend());

// 注意  这里最后要整合在一起的.
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/default/contextmenu.js'), StaticPluginHelper::getDepend() );
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/default/page.js'), StaticPluginHelper::getDepend());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/default/socket.js'), StaticPluginHelper::getDepend());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/default/chat.js'), StaticPluginHelper::getDepend());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/default/index.js'), StaticPluginHelper::getDepend());

?>
<script>
    WEB_SOCKET_SWF_LOCATION = '<?=GlobalUrlService::buildStaticUrl('/socket/WebSocketMain.swf')?>';
    online_users = Object.values(<?=json_encode($online_users)?>);
    offline_users= Object.values(<?=json_encode($offline_users)?>);
    all_users = Object.values(<?=json_encode($all_users)?>);
</script>
<style>
    .layui-layer-shade {
        display: none !important;
    }
</style>
<div id='chatExe'>
    <!-- 右键菜单 -->
    <div id="menu" style="z-index: 1">
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
<!--                    <i class="iconfont icon-shezhi icon icon-action fsize32" title="设置"></i>-->
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
                <span class="exe-header-info-kfname"><?=$this->params['current_user']['nickname']?></span>
                <span><i class="iconfont icon-guanbi"></i></span>
            </div>
        </div>
        <div class="exe-content-info dflex">
            <div class="exe-content">
                <div class="exe-content-top">
                    <div class="content-top-info">
                    <!-- <div class="info keyword"><span>关键词：</span><span>-</span></div>-->
                        <div class="info land-url"><span>落地页：</span><span class="land-url-url">-</span>
                            <span data-clipboard-text="" class="landUrl-copy dis_none">复制</span></div>
                        <div class="info canal-url"><span>渠道：</span><span>-</span></div>
                    </div>
                    <div class="content-top-info">
                        <div class="info source"><span>关键词：</span><span>-</span></div>
                        <div class="info referer-url"><span>来源：</span><span>-</span><span data-clipboard-text="" class="referer-url-copy dis_none">复制</span></div>
                    </div>
                </div>
                <div class="exe-content-history">
                    <div class="exe-content-history-load">
                        <div class="iconfont icon-jiazaizhong dis_none"></div>
                        <div class="history-look">查看更多消息</div>
                        <div class="exe-content-history-content-null"></div>
                    </div>
                    <div class="exe-content-history-ready"></div>
                    <div class="exe-content-history-title">
                    </div>
                    <div class="exe-content-history-content"></div>
                </div>
                <div class="exe-content-sumbit" style="position: relative;">
                    <div>
                        <span>
                            <i class="iconfont icon-biaoqing" id="openFace"></i>
                        </span>
                    </div>

                    <div class="faceDivBox" style="display:none;height: 170px; bottom: 155px;max-width: 100%;left: 13px;top: -175px;">
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
                        <div class="tab-one">记录中心</div>
                    </div>
                    <div class="tab-content">
                        <div class="content-one content-tab-one">
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
                                <span class="desc" style="width: 150px;word-break: break-all;    overflow: hidden; white-space: nowrap;text-overflow: ellipsis;">
                                </span>
                            </div>
                        </div>
                        <div class="content-one content-tab-two access-track" style="display: none;max-height: 220px;">
                            <div style="text-align: center;color: rgb(179, 181, 185) !important;">暂无</div>
                        </div>
                    </div>
                </div>
                <div class="tab fGrow1 two">
                    <div class="tab-switch height10 height50" >
                        <div class="tab-one tab_common_word">
                            <a class="refresh_word" href="javascript:void(0);" style="color:black;">常用语</a>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="content-one">
                            <div class="words-content" style="height: 62%;">
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
    <!-- 设置弹出层 -->
        <div class="edit_bg"></div>
        <!-- 彈出内容 -->
        <div class="edit_content">
            <div class="edit_tab">
                <div class="edit_tabs edit_tabs_active">个人信息</div>
                <div class="edit_tabs">提醒方式</div>
                <div class="edit_tabs">快捷键</div>
                <div class="edit_tabs">自动消息</div>
                <div class="edit_tabs">设置</div>
                <i class="iconfont icon-htmal5icon21 edit_guanbi"></i>
            </div>
            <div class="edit_tab_content">
                <div class="edit_content_list content_list_one">
                    <div class="title">
                        <div>回呼号码</div>
                    </div>
                    <div class="tip">访客拨打时 ( <span>预览具体外观</span> ) ，被呼叫的客服手机号</div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">客服手机号</div>
                        <div><input></div>
                    </div>
                    <div class="title" style="    margin-top: 30px;">
                        <div>客服名片  (对外)</div>
                    </div>
                    <div class="tip">客服名片展示对象：所有的访客用户，未填入的信息将不展示 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span>预览具体外观</span> </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">头像</div>
                        <div class="form_file_info">
                            <img src="https://s0up.53kf.com/g1/M00/00/01/wKhvsFluASKEE9dUAAAAACBtce4142.png">
                            <div class="">
                                <div class="form_file_button">上传头像</div>
                                <div class="button_file_info">支持 JPG/PNG/GIF 格式的图片</div>
                            </div>
                        </div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">电话</div>
                        <div><input></div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">手机</div>
                        <div><input></div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">Q Q</div>
                        <div><input></div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">微信</div>
                        <div><input></div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">邮箱</div>
                        <div><input></div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">个人简介</div>
                        <div><textarea></textarea></div>
                    </div>
                </div>
                <div class="edit_content_list content_list_one">
                    <div class="title">
                        <div>弹出方式</div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">收到访客消息</div>
                        <div class="form_title_select">
                            <input name="radio1" type="radio"  title="弹出窗口">弹出窗口
                            <input name="radio1" type="radio">弹出通知
                            <input name="radio1"type="radio">不通知</div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">访客建立对话</div>
                        <div class="form_title_select">
                            <input name="radio1" type="radio"  title="弹出窗口">弹出窗口
                            <input name="radio1" type="radio">弹出通知
                            <input name="radio1"type="radio">不通知</div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">访客上线</div>
                        <div class="form_title_select"><input type="checkbox"> 弹出通知<span style="color:#b2bac1">(为电脑性能考虑, 当访客超过500将不再执行)</span></div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">访客排队</div>
                        <div class="form_title_select"><input type="checkbox"> 弹出通知</div>

                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">访客转换</div>
                        <div class="form_title_select"><input type="checkbox"> 弹出通知</div>

                    </div>
                    <div class="title">
                        <div>声音通知</div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">访客上线</div>
                        <div class="form_title_select" style="display: block;">
                            <select>
                                <option value="volvo">Volvo</option>
                                <option value="saab">Saab</option>
                                <option value="opel">Opel</option>
                                <option value="audi">Audi</option>
                            </select><br>
                            <span style="color:#b2bac1">(为电脑性能考虑, 当访客超过500将不再执行)</span>
                        </div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">访客建立对话</div>
                        <div class="form_title_select">
                            <select>
                                <option value="volvo">Volvo</option>
                                <option value="saab">Saab</option>
                                <option value="opel">Opel</option>
                                <option value="audi">Audi</option>
                            </select></div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">收到访客消息</div>
                        <div class="form_title_select">
                            <select>
                                <option value="volvo">Volvo</option>
                                <option value="saab">Saab</option>
                                <option value="opel">Opel</option>
                                <option value="audi">Audi</option>
                            </select>
                    </div>
                </div>
                    <div class="title">
                        <div>微信服务通知</div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">微信服务通知</div>
                        <div class="form_title_select">
                           <div>❤ 包括访客留言、下载完成、工单处理进度等通知</div>
                        </div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info"></div>
                        <div class="form_title_select">
                            <img src="https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=2497317386,3587338906&fm=26&gp=0.jpg">
                        </div>
                    </div>
                    <div class="title">
                        <div>未回复超时提醒</div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">对话列表红环提醒</div>
                        <div class="form_title_select">
                            <input name="radio1" type="radio" >10秒
                            <input name="radio1" type="radio">15秒
                            <input name="radio1"type="radio">20秒
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="tip"  style="margin-bottom: 0px"><span>预览具体外观</span> </div>

                        </div>
                    </div>
                </div>
                <div class="edit_content_list content_list_one">
                    <div class="title">
                        <div>回呼号码</div>
                    </div>
                    <div class="tip">只能输入数字 0-9 或字母 A-Z</div>
                    <div class="list_title_size">系统通用：</div>
                    <div class="list_win">
                        <div class="win_edit_anJ">Shift+&nbsp;Alt+<div class="win_edit" contenteditable="true">Z</div></div>
                        <div>展开客服端生效<span style="color: #627484;padding-left: 5px">(重新登录后生效)</span></div>
                    </div>
                    <div class="list_win">
                        <div class="win_edit_anJ"><div class="win_button">▲</div><div class="win_button">▼</div></div>
                        <div>向上或向下移动</span></div>
                    </div>
                    <div class="list_win">
                        <div class="win_edit_anJ"><div  class="win_button">◀</div><div  class="win_button">▶</div></div>
                        <div>向左或向右移动</span></div>
                    </div>
                    <div class="list_title_size">访客消息：</div>
                    <div class="list_win">
                        <div class="win_edit_anJ">Shift+&nbsp;Alt+<div class="win_edit" contenteditable="true">A</div></div>
                        <div>截图<span style="color: #627484;padding-left: 5px">(重新登录后生效)</span></div>
                    </div>
                    <div class="list_win">
                        <div class="win_edit_anJ">Shift+&nbsp;Alt+<div class="win_edit" contenteditable="true">X</div></div>
                        <div>访客未读消息</div>
                    </div>
                    <div class="list_win">
                        <div class="win_edit_anJ">Shift+&nbsp;Alt+<div  class="win_button">▲</div></div>
                        <div>上一个对话</div>
                    </div>
                    <div class="list_win">
                        <div class="win_edit_anJ">Shift+&nbsp;Alt+<div  class="win_button">▼</div></div>
                        <div>下一个对话</div>
                    </div>
                    <div class="list_win">
                        <div class="win_edit_anJ">Shift+&nbsp;Alt+<div class="win_edit" contenteditable="true">Z</div></div>
                        <div>访客名片编辑</div>
                    </div>
                    <div class="list_win">
                        <div class="win_edit_anJ">Shift+&nbsp;Alt+<div class="win_edit" contenteditable="true">Z</div></div>
                        <div>常用语搜索</div>
                    </div>
                    <div class="list_win">
                        <div class="win_edit_anJ">Shift+&nbsp;Alt+<div class="win_edit" contenteditable="true">Z</div></div>
                        <div>结束访客对话</div>
                    </div>
                </div>
                <div class="edit_content_list content_list_one">
                    <div class="title">
                        <div>欢迎语</div>
                    </div>
                    <div class="tip">接通后，自动推送给访客的消息，最多可设置为 8 条</div>
                    <div class="edit_welocme_form">
                        <div class="form_title_info"><input type="checkbox"></div>
                        <div class="form_file_info">在「客服端」显示欢迎语</div>

                    </div>
                    <div class="edit_welocme_form">
                        <div class="form_title_info"></div>
                        <div class="form_file_info" style="color: #8d9dab">得知访客在哪句说了话，从而根据上下文，进行接下来的沟通</div>
                    </div>
                    <div class="edit_welocme_form" style="margin: 15px 0">
                        <div class="form_file_info">
                            在访客未回复前，最多发送 <input type="number" min="1" value="1">轮
                        </div>
                    </div>
                    <div class="edit_welocme_form" style="margin: 15px 0">
                        <div class="form_file_info">
                            <div class="edit_image_file">
                                <i class="iconfont icon-jiahao1"></i>
                            </div>
                        </div>
                    </div>
                    <div class="title">
                        <div>客服繁忙</div>
                    </div>
                    <div class="tip">一段时间内未回复访客，自动推送给访客的消息</div>
                <div class="edit_welocme_form" style="margin: 15px 0">
                    <div class="form_file_info">
                        客服超过 <input type="number" min="1" value="1">秒无反应自动发送
                    </div>
                </div>
                <div class="edit_welocme_form" style="margin: 15px 0">
                    <div class="form_file_info">
                        <textarea></textarea>
                    </div>
                </div>
                <div class="title">
                    <div>对话结束</div>
                </div>
                <div class="tip">对话结束后，自动推送给访客的消息</div>
                <div class="edit_welocme_form" style="margin: 15px 0">
                <div class="form_file_info">
                    <textarea></textarea>
                </div>
            </div></div>
                <div class="edit_content_list content_list_one" >
                    <div class="title">
                        <div>「客服端」模块设置</div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">访问信息</div>
                        <div class="form_title_select" style="    padding: 0 14px;">
                            <input name="radio1" type="checkbox"  title="弹出窗口">关键词
                            <input name="radio1" type="checkbox"  title="弹出窗口">来源
                            <input name="radio1" type="checkbox"  title="弹出窗口">咨询页
                            <input name="radio1" type="checkbox"  title="弹出窗口">渠道
                            <input name="radio1" type="checkbox"  title="弹出窗口">着陆页
                            <input name="radio1" type="checkbox"  title="弹出窗口">风格
                            <span style="color: #fe8854;cursor: pointer; margin-left: 4px;">预览具体外观</span>
                        </div>
                    </div>
                    <div class="title">
                        <div>登录设置</div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">自动登录</div>
                        <div class="form_title_select">
                            <input name="radio1" type="checkbox"  title="弹出窗口">打开「客服端」后为我自动登录
                        </div>
                    </div>
                    <div class="title">
                        <div>其他设置</div>
                    </div>
                    <div class="edit_tab_form" >
                        <div class="form_title_info">自动「小休」</div>
                        <div class="edit_welocme_form" style="margin: 15px 0;    padding: 0 14px;">
                            <div class="form_file_info">
                                <input name="radio1" type="checkbox"  title="弹出窗口"> 超过 <input type="number" min="1" value="1">分钟处于非活动状态，自动切换为「小休」
                                <span style="color: #fe8854;cursor: pointer; margin-left: 4px;">预览具体外观</span>
                            </div>
                        </div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">智能输入</div>
                        <div class="form_title_select">
                            <input name="radio1" type="checkbox"  title="弹出窗口">输入时通过关键字自动匹配相关的常用语<span style="color: #fe8854;cursor: pointer; margin-left: 4px;">预览具体外观</span>
                        </div>
                    </div>
                    <div class="edit_tab_form">
                        <div class="form_title_info">显示上次聊天记录</div>
                        <div class="form_title_select">
                            <input name="radio1" type="checkbox"  title="弹出窗口">与访客建立新的对话，会显示上次部分聊天记录
                        </div>
                    </div>
                </div>
            </div>
            <!-- 提交 -->
            <div class="edit_submit">
                <div class="submit">保存更改</div>
                <div class="revoke">还原更改</div>
            </div>
        </div>
</div>

<div class="hidden_wrapper">
    <input type="hidden" name="params" value='<?=json_encode($js_params);?>'>
    <audio controls id="tip_music">
        <source src="http://chat-resource.cdn.corp.hsh568.cn/audio/dingdong.mp3" type="audio/mpeg">
        <embed  src="http://chat-resource.cdn.corp.hsh568.cn/audio/dingdong.mp3">
    </audio>
</div>
<?php
use \common\services\GlobalUrlService;
use \common\components\helper\StaticAssetsHelper;
StaticAssetsHelper::includeAppJsStatic( GlobalUrlService::buildWwwStaticUrl("/js/merchant/computer/index/index.js"),www\assets\MerchantAsset::className() )
?>
<!--表单使用 以及 layUI参考 https://www.layui.com/doc/modules/upload.html-->
<div id="staff_index_index">
    <div class="staff_tab">
        <div class="tab_list " ><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/style/index/index');?>">风格列表</a></div>
        <div class="tab_list tab_active"><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/style/computer/index');?>">PC端设置</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/style/mobile/index');?>">移动端设计</a></div>
        <div class="tab_list " ><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/style/newsauto/index');?>">自动消息</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/style/reception/index');?>">接待规则</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/style/video/index');?>">视频</a></div>
    </div>
    <div class="tab_staff_content">
        <ul class="mainmenu">
            <li>
                <span>通用设置</span>
            </li>
            <ul class="submenu">
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">输入框</label>
                        <div class="layui-input-block">
                            <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">密码框</label>
                        <div class="layui-input-inline">
                            <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux">辅助文字</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">选择框</label>
                        <div class="layui-input-block">
                            <select name="city" lay-verify="required">
                                <option value=""></option>
                                <option value="0">北京</option>
                                <option value="1">上海</option>
                                <option value="2">广州</option>
                                <option value="3">深圳</option>
                                <option value="4">杭州</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">文件上传</label>
                        <div class="layui-input-block">
                            <button type="button" class="layui-btn" id="test1">
                                <i class="layui-icon">&#xe67c;</i>上传图片
                            </button>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">颜色选择器</label>
                        <div class="layui-input-inline">
                            <div id="test2"></div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">按钮切换</label>
                        <div class="layui-input-inline ">
                                <ul class="layui-tab-title">
                                    <li class="layui-this">网站设置</li>
                                    <li>用户管理</li>
                                </ul>
                                <div class="layui-tab-content"></div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">树形菜单</label>
                        <div class="layui-input-inline ">
                            <div id="test3"></div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">富文本</label>
                        <div class="layui-input-inline ">
                            <textarea id="demo4" style="display: none;"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">复选框</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="like[write]" title="写作">
                            <input type="checkbox" name="like[read]" title="阅读" checked>
                            <input type="checkbox" name="like[dai]" title="发呆">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">开关</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="switch" lay-skin="switch">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">单选框</label>
                        <div class="layui-input-block">
                            <input type="radio" name="sex" value="男" title="男">
                            <input type="radio" name="sex" value="女" title="女" checked>
                        </div>
                    </div>
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">文本域</label>
                        <div class="layui-input-block">
                            <textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </form>
            </ul>
            <li>
                <span>主动发起</span>
            </li>
            <ul class="submenu">
                <li><span>New</span></li>
                <li><span>Sent</span></li>
                <li><span>Trash</span></li>
            </ul>
            <li>
                <span>邀请框</span>
            </li>
            <ul class="submenu">
                <li><span>Language</span></li>
                <li><span>Password</span></li>
                <li><span>Notifications</span></li>
                <li><span>Privacy</span></li>
                <li><span>Payments</span></li>
            </ul>
            <li>
                <span>浮动窗口</span>
            </li>
            <ul class="submenu">
                <li><span>Language</span></li>
                <li><span>Password</span></li>
                <li><span>Notifications</span></li>
                <li><span>Privacy</span></li>
                <li><span>Payments</span></li>
            </ul>
            <li>
                <span>新窗口</span>
            </li>
            <ul class="submenu">
                <li><span>Language</span></li>
                <li><span>Password</span></li>
                <li><span>Notifications</span></li>
                <li><span>Privacy</span></li>
                <li><span>Payments</span></li>
            </ul>
            <li>
                <span>客服图标</span>
            </li>
            <ul class="submenu">
                <li><span>Language</span></li>
                <li><span>Password</span></li>
                <li><span>Notifications</span></li>
                <li><span>Privacy</span></li>
                <li><span>Payments</span></li>
            </ul>
            <li>
                <span>留言</span>
            </li>
            <ul class="submenu">
                <li><span>Language</span></li>
                <li><span>Password</span></li>
                <li><span>Notifications</span></li>
                <li><span>Privacy</span></li>
                <li><span>Payments</span></li>
            </ul>
        </ul>
    </div>
    </div>
</div>
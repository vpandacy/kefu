<?php
use \common\services\GlobalUrlService;
use \common\components\helper\StaticAssetsHelper;
StaticAssetsHelper::includeAppJsStatic( GlobalUrlService::buildWwwStaticUrl("/js/merchant/computer/index/index.js"),www\assets\MerchantAsset::className() )
?>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'style',
        'current_menu'  =>  'computer'
    ])?>
    <div class="tab_staff_content">
        <ul class="mainmenu">
            <li>
                <span>通用设置</span>
            </li>
            <ul class="submenu">
                <div class="site-text">
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">公司名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
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

                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">公司简介</label>
                        <div class="layui-input-block">
                            <textarea name="desc" placeholder="请输入公司简介" class="layui-textarea"></textarea>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">展示历史记录</label>
                        <div class="layui-input-block">
                            <input type="radio" name="status" value="1" title="是">
                            <input type="radio" name="status" value="0" title="否" checked>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">启用范围区域</label>
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
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="formDemo">保存</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </form>
                </div>
            </ul>
            <li>
                <span>主动发起</span>
            </li>
            <ul class="submenu">
                <form action="" class="layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">开启主动发起</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="" title="开启">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">发送方式</label>
                        <div class="layui-input-block">
                            <input type="radio" name="status" value="1" title="浮动窗口">
                            <input type="radio" name="status" value="0" title="邀请框">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">浮窗初始状态</label>
                        <div class="layui-input-block">
                            <input type="radio" name="status" value="1" title="浮动窗口">
                            <input type="radio" name="status" value="0" title="邀请框">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">延时发起</label>
                        <div class="layui-input-block">
                            <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">是否重复发起</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="" title="开启">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">发起间隔</label>
                        <div class="layui-input-block">
                            <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">发起次数</label>
                        <div class="layui-input-block">
                            <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">新消息强制展开</label>
                        <div class="layui-input-block">
                            <input type="radio" name="status" value="1" title="浮动窗口">
                            <input type="radio" name="status" value="0" title="邀请框">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">离线自动发起</label>
                        <div class="layui-input-block">
                            <input type="radio" name="status" value="1" title="浮动窗口">
                            <input type="radio" name="status" value="0" title="邀请框">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="formDemo">保存</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </form>
            </ul>
        </ul>
    </div>
    </div>
</div>
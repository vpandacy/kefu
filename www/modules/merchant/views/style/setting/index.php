<?php
use common\components\helper\StaticPluginHelper;
use www\assets\MerchantAsset;
use common\components\helper\StaticAssetsHelper;
use common\services\GlobalUrlService;

StaticPluginHelper::setDepend(MerchantAsset::className());

StaticPluginHelper::qiniuPlugin();

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/style/setting/index.js'),MerchantAsset::className());
?>
<div id="staff_index_index">
    <div class="staff_tab">
        <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
            'bar_menu'  =>  'style',
            'current_menu'  =>  'setting'
        ])?>
    </div>
    <div class="tab_staff_content submenu">
        <form action="" class="layui-form">
            <div class="site-text">
                <fieldset class="layui-elem-field">
                    <legend>风格分组</legend>
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">请选择风格</label>
                            <div class="layui-input-block">
                                <select name="group_chat_id" lay-filter="choice">
                                    <option value="0">普通风格</option>
                                    <?php foreach($groups as $group):?>
                                        <option value="<?=$group['id']?>"><?=$group['title']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>公司信息</legend>
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">公司名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="company_name" value=""  required="" lay-verify="required" placeholder="请输入公司名/商户名" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">公司LOGO</label>
                            <div class="layui-input-block" id="upload_container">
                                <button type="button" class="layui-btn" id="logo">
                                    <i class="layui-icon"></i>上传图片
                                </button>
                                <input type="hidden" value="" name="company_logo">
                            </div>
                            <div class="img-wrapper" style="margin-top: 10px;">
                            </div>
                        </div>

                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">公司简介</label>
                            <div class="layui-input-block">
                                <textarea name="company_desc" placeholder="请输入公司简介" lay-verify="required" class="layui-textarea"></textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>基本配置</legend>
                    <div class="layui-field-box">

                        <div class="layui-form-item">
                            <label class="layui-form-label">延时发起</label>
                            <div class="layui-input-block">
                                <input type="text" name="lazy_time" value=""  required="" lay-verify="required" placeholder="请输入公司名/商户名" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">所在省份</label>
                            <div class="layui-input-block">
                                <select name="province_id">
                                    <option value="0">普通风格</option>
                                    <?php foreach($groups as $group):?>
                                        <option value="<?=$group['id']?>"><?=$group['title']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">展示消息记录</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_history" value="0" title="是">
                                <input type="radio" name="is_history" value="1" title="否">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">主动发起对话</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_active" value="0" title="是">
                                <input type="radio" name="is_active" value="1" title="否">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">浮窗初始状态</label>
                            <div class="layui-input-block">
                                <input type="radio" name="windows_status" value="0" title="最小化">
                                <input type="radio" name="windows_status" value="1" title="展示">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">新消息强制弹窗</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_force" value="0" title="是">
                                <input type="radio" name="is_force" value="1" title="否">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">展示消息数量</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_show_num" value="0" title="是">
                                <input type="radio" name="is_show_num" value="1" title="否">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="info">立即保存</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </form>
    </div>
</div>
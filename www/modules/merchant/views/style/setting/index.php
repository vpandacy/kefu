<?php
use common\components\helper\StaticPluginHelper;
use www\assets\MerchantAsset;
use common\components\helper\StaticAssetsHelper;
use common\services\GlobalUrlService;

StaticPluginHelper::setDepend(MerchantAsset::className());

StaticPluginHelper::qiniuPlugin();
StaticPluginHelper::umeditor();

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/style/setting/index.js'),MerchantAsset::className());
?>
<style>
    .layui-input-block {
        width: 215px;
    }
    .w115 {
        width: 115px;
    }
    .pleft31 {
        padding-left: 31px;
    }
    .pleft40 {
        padding-left: 40px;
    }
    .layui-field-box {
        padding: 10px 0;
    }
    .layui-textarea {
        height: 38px;
        min-height: 38px;
    }
</style>
<div id="staff_index_index">
    <div class="staff_tab">
        <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
            'bar_menu'  =>  'style',
            'current_menu'  =>  'setting'
        ])?>
    </div>
    <div class="tab_staff_content">
        <form action="" class="layui-form">
            <div class="site-text">
                <fieldset class="layui-elem-field">
                    <legend>风格分组</legend>
                    <div class="layui-field-box">
                        <div class="layui-form-item pleft31">
                            <label class="layui-form-label" >请选择风格</label>
                            <div class="layui-input-block">
                                <select name="group_chat_id" lay-filter="choice">
                                    <option value="0">默认风格</option>
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
                        <div class="layui-form-item pleft31">
                            <label class="layui-form-label">公司名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="company_name" value=""  required="" lay-verify="required" placeholder="请输入公司名/商户名" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item pleft31">
                            <label class="layui-form-label"  >公司LOGO</label>
                            <div class="layui-input-block" id="upload_container">
                                <button type="button" class="layui-btn" id="logo">
                                    <i class="layui-icon"></i>上传图片
                                </button>
                                <input type="hidden" value="" name="company_logo">
                            </div>
                            <div class="img-wrapper" style="margin-top: 10px;">
                            </div>
                        </div>

                        <div class="layui-form-item layui-form-text pleft31">
                            <label class="layui-form-label" >公司简介</label>
                            <div class="layui-input-block">
                                <textarea name="company_desc" placeholder="请输入公司简介" lay-verify="required" class="layui-textarea"></textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>基本配置</legend>
                    <div class="layui-field-box">

<!--                        <div class="layui-form-item">-->
<!--                            <label class="layui-form-label">延时发起</label>-->
<!--                            <div class="layui-input-block">-->
<!--                                <input type="text" name="lazy_time" value=""  required="" lay-verify="required" placeholder="请输入公司名/商户名" autocomplete="off" class="layui-input">-->
<!--                            </div>-->
<!--                        </div>-->

<!--                        <div class="layui-form-item">-->
<!--                            <label class="layui-form-label">所在省份</label>-->
<!--                            <div class="layui-input-block">-->
<!--                                <select name="province_id">-->
<!--                                    <option value="0">所有省份</option>-->
<!--                                    --><?php //foreach($city as $c):?>
<!--                                        <option value="--><?//=$c['id']?><!--">--><?//=$c['name']?><!--</option>-->
<!--                                    --><?php //endforeach;?>
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="layui-form-item">
                            <label class="layui-form-label w115" style="width: 120px">展示消息记录</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_history" value="0" title="是">
                                <input type="radio" name="is_history" value="1" title="否">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label w115"  style="width: 120px">浮窗初始状态</label>
                            <div class="layui-input-block">
                                <input type="radio" name="windows_status" value="0" title="最小化">
                                <input type="radio" name="windows_status" value="1" title="展示">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label w115"  style="width: 120px">新消息强制弹窗</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_force" value="0" title="是">
                                <input type="radio" name="is_force" value="1" title="否">
                            </div>
                        </div>

<!--                        <div class="layui-form-item">-->
<!--                            <label class="layui-form-label">展示消息数量</label>-->
<!--                            <div class="layui-input-block">-->
<!--                                <input type="radio" name="is_show_num" value="0" title="是">-->
<!--                                <input type="radio" name="is_show_num" value="1" title="否">-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </fieldset>


                <fieldset class="layui-elem-field">
                    <legend>发起配置</legend>
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label w115" style="width: 120px">重复发起</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_repeat" value="0" title="否">
                                <input type="radio" name="is_repeat" value="1" title="是">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label" style="width: 120px">发起语句设置</label>
                            <div class="layui-input-block" style="margin-left: 150px;width: auto">
                                <table class="layui-table" id="repeatTable" lay-filter="repeatTable">
                                </table>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <div class="layui-input-block pleft40">
                                <button class="layui-btn" lay-submit="" lay-filter="info">立即保存</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </form>
    </div>
</div>

<script type="text/html" id="tool">
    <button type="button" class="layui-btn layui-btn-sm" lay-event="add">新增</button>
</script>

<script type="text/html" id="toolbar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
</script>

<div class="publish-form dis_none">
    <form action="" class="layui-form">
    <form action="" class="layui-form">
        <div class="layui-form-item time_star">
            <label for="" class="layui-form-label">发起时间(秒)</label>
            <div class="layui-inline">
                <input type="text" name="time" value=""  required="" lay-verify="required" placeholder="请输入发起时间(秒)" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">发起内容</label>
            <div class="layui-inline" style="width: 600px;">
                <textarea name="content" placeholder="请输入发起内容" lay-verify="required" class="layui-textarea"></textarea>
            </div>
        </div>
    </form>
</div>
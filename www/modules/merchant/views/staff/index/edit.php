<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/plugins/qiniu/plupload/moxie.min.js'), MerchantAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/plugins/qiniu/plupload/plupload.full.min.js'), MerchantAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/plugins/qiniu/plupload/zh_CN.js'), MerchantAsset::className());

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/plugins/qiniu/qiniu.min.js'), MerchantAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwUrl('/js/merchant/staff/index/edit.js'), MerchantAsset::className());
?>
<div id="staff_index_index">
    <div class="staff_tab">
        <div class="tab_list tab_active"><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/index/index');?>">子账号管理</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/department/index');?>">部门管理</a></div>
        <div class="tab_list"><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/role/index');?>">角色管理</a></div>
    </div>
    <div class="tab_staff_content">
        <table class="layui-hide" id="test" style="position: relative">
        </table>
    </div>
</div>

<ul class="submenu">
    <form class="layui-form" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">姓名/商户名</label>
            <div class="layui-input-block">
                <input type="text" name="name" value="<?=$staff['name']?>" lay-verify="required" placeholder="请输入姓名或商户名" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-block">
                <input type="text" name="email" value="<?=$staff['email']?>" lay-verify="required" placeholder="请输入邮件地址" <?=$staff['email'] ? 'disabled' : ''?> autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-block">
                <input type="text" name="mobile" value="<?=$staff['mobile']?>" lay-verify="required|phone" placeholder="请输入手机号" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">接听数</label>
            <div class="layui-input-block">
                <input type="text" name="listen_nums" value="<?=$staff['listen_nums']?>" lay-verify="required" placeholder="请输入接听数量,0代表没有上限" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">请选择部门</label>
            <div class="layui-input-block">
                <select name="department_id" lay-verify="required">
                    <option value="0">请选择部门</option>
                    <option value="0">北京</option>
                    <option value="1">上海</option>
                    <option value="2">广州</option>
                    <option value="3">深圳</option>
                    <option value="4">杭州</option>
                </select><div class="layui-unselect layui-form-select"><div class="layui-select-title"><input type="text" placeholder="请选择" value="" readonly="" class="layui-input layui-unselect"><i class="layui-edge"></i></div><dl class="layui-anim layui-anim-upbit"><dd lay-value="" class="layui-select-tips">请选择</dd><dd lay-value="0" class="">北京</dd><dd lay-value="1" class="">上海</dd><dd lay-value="2" class="">广州</dd><dd lay-value="3" class="">深圳</dd><dd lay-value="4" class="">杭州</dd></dl></div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">请上传头像</label>
            <div class="layui-input-block">
                <button class="layui-btn" id="upload_container">
                    <i class="layui-icon"></i>上传头像
                </button>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">密码框</label>
            <div class="layui-input-inline">
                <input type="password" name="password" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">如需修改密码请输入</div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input type="password" name="confirm_password" placeholder="请输入确认密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">如需修改密码请输入</div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="staffFrom">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</ul>

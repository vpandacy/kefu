<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;


StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/merchant/staff/department/index.js'), MerchantAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl("/layui/v2.5/layui.all.js"), MerchantAsset::className());
?>
<div id="staff_index_index">
    <div class="staff_tab">
        <div class="tab_list">
            <a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/index/index');?>">子账号管理</a>
        </div>
        <div class="tab_list tab_active">
            <a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/department/index');?>">部门管理</a>
        </div>
        <div class="tab_list">
            <a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/role/index');?>">角色管理</a>
        </div>
    </div>
    <div class="tab_staff_content">
        <table class="layui-hide" lay-filter="departmentTable" id="departmentTable"></table>
    </div>
</div>

<script type="text/html" id="departToolbar">
    <div class="layui-btn-container" style="display: block">
        <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
        <button class="layui-btn layui-btn-sm" lay-event="isAll">恢复</button>
    </div>
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="disable">禁用</a>
</script>
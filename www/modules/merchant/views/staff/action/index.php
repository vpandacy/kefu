<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/merchant/staff/action/index.js'), MerchantAsset::className());
?>
<div id="staff_index_index">
    <div class="staff_tab">
        <div class="tab_list">
            <a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/index/index');?>">子账号管理</a>
        </div>
        <div class="tab_list ">
            <a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/department/index');?>">部门管理</a>
        </div>
        <div class="tab_list">
            <a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/role/index');?>">角色管理</a>
        </div>
        <div class="tab_list tab_active">
            <a href="<?=GlobalUrlService::buildMerchantUrl('/staff/action/index')?>">权限管理</a>
        </div>
    </div>
    <div class="tab_staff_content">
        <div class="demoTable" style="    text-align: left;margin:10px 18px;">
            <button class="layui-btn" data-type="reload">搜索</button>
            <div class="layui-inline">
                <input class="layui-input" name="id" id="demoReload" autocomplete="off">
            </div>
        </div>
        <table class="layui-hide" id="test"></table>
    </div>
</div>

<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm" lay-event="getCheckData">添加</button>
        <button class="layui-btn layui-btn-sm" lay-event="isAll">恢复</button>
    </div>
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>


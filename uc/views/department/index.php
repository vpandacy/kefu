<?php
use common\components\helper\StaticAssetsHelper;
use common\services\GlobalUrlService;
use uc\assets\UcAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildUcStaticUrl('/js/department/index.js'), UcAsset::className());
?>
<style>
    .layui-input-block {
        width: 150px;
    }
</style>
<div id="staff_index_index">
    <?=$this->renderFile('@uc/views/common/bar_menu.php',[
        'bar_menu'  =>  'user',
        'current_menu'  =>  'department'
    ])?>
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
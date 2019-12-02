<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic( GlobalUrlService::buildWwwStaticUrl("/js/merchant/staff/index/index.js"),MerchantAsset::className() )
?>

<!--  表格用的layui 具体配置参考：https://www.layui.com/demo/table/auto.html
      所有页面的表格以此页面表格为标准.
-->
<div id="staff_index_index">
    <div class="staff_tab">
        <div class="tab_list tab_active" ><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/index/index');?>">子账号管理</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/department/index');?>">部门管理</a></div>
        <div class="tab_list"><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/role/index');?>">角色管理</a></div>
    </div>
    <div class="tab_staff_content">
        <table class="layui-hide" id="test" style="position: relative">
        </table>
    </div>
</div>
<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <div>
            <button class="layui-btn layui-btn-sm" lay-event="getCheckData">添加</button>
            <button class="layui-btn layui-btn-sm" lay-event="isAll">恢复</button>
        </div>
        <div>
            <i class="fa fa-glass" aria-hidden="true" title="筛选"></i>
        </div>
    </div>
</script>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
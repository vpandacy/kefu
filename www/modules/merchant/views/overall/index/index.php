<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppCssStatic( GlobalUrlService::buildWwwStaticUrl("/css/merchant/staff/index/index.css"),MerchantAsset::className());

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl("/layui/v2.5/layui.all.js"), MerchantAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/merchant/overall/index/index.js'), MerchantAsset::className());
?>
<div id="staff_index_index">
    <div class="staff_tab">
        <div class="tab_list tab_active" ><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/index/index');?>">常用语管理</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/clueauto/index');?>">线索自动采集</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/breakauto/index');?>">自动断开</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/company/index');?>">企业设置</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/offline/index');?>">离线表单设置</a></div>
    </div>
    <div class="tab_staff_content">
        <table class="layui-hide" id="test"></table>
    </div>
</div>

<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm" lay-event="getCheckData">添加</button>
<!--        <button class="layui-btn layui-btn-sm" lay-event="isAll">恢复</button>-->
    </div>
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
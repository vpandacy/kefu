<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;
use \common\components\helper\StaticPluginHelper;
StaticPluginHelper::setDepend( MerchantAsset::className() );
StaticAssetsHelper::includeAppCssStatic( GlobalUrlService::buildKFStaticUrl("/css/merchant/staff/index/index.css"),StaticPluginHelper::getDepend() );

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl("/layui/v2.5/layui.all.js"), StaticPluginHelper::getDepend() );
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/overall/index/index.js'), StaticPluginHelper::getDepend() );
?>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'settings',
        'current_menu'  =>  'common_words'
    ])?>
    <div class="tab_staff_content">
        <table class="layui-hide" lay-filter="commonWordTable" id="commonWordTable"></table>
    </div>
</div>

<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container" style="display: block">
        <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
        <button class="layui-btn layui-btn-sm" lay-event="recover">恢复</button>
        <button class="layui-btn layui-btn-sm" lay-event="import">导入</button>
        <button class="layui-btn layui-btn-sm" lay-event="disableAll">全部禁用</button>
    </div>
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="disable">禁用</a>
</script>
<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic( GlobalUrlService::buildKFStaticUrl('/js/merchant/black/index/index.js'), MerchantAsset::className() )
?>

<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'blacklist',
        'current_menu'  =>  'blacklist'
    ])?>
    <div class="tab_staff_content">
        <table class="layui-hide" lay-filter="blackListTable" id="blackListTable"></table>
    </div>
</div>
<script type="text/html" id="blackToolbar">
    <div class="layui-btn-container" style="display: block">
        <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
    </div>
</script>

<script type="text/html" id="blackBar">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
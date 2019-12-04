<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;


StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/merchant/staff/role/index.js'), MerchantAsset::className())

?>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'user',
        'current_menu'  =>  'role'
    ])?>

    <div class="tab_staff_content">
        <table class="layui-hide" lay-filter="roleTable" id="roleTable" style="position: relative">
        </table>
    </div>
</div>
<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container" style="display: block">
        <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
        <button class="layui-btn layui-btn-sm" lay-event="isAll">恢复</button>
    </div>
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">禁用</a>
</script>
<script src="<?=GlobalUrlService::buildStaticUrl("/layui/v2.5/layui.all.js");?>"></script>
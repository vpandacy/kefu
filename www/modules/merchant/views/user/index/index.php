<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/user/index/index.js'), MerchantAsset::className())
?>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'member',
        'current_menu'  =>  'member'
    ])?>
    <div class="tab_staff_content">
        <table class="layui-hide" id="userTable" lay-filter="userTable"></table>
    </div>
</div>

<script type="text/html" id="userBar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
</script>
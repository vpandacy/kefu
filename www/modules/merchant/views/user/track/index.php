<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/user/track/index.js'), MerchantAsset::className())
?>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'message',
        'current_menu'  =>  'track'
    ])?>
    <div class="tab_staff_content">
        <table class="layui-hide" id="trackTable" lay-filter="trackTable"></table>
    </div>
</div>
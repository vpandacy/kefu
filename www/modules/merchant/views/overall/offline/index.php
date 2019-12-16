<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/offline/index.js'),MerchantAsset::className());
?>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'message',
        'current_menu'  =>  'leave'
    ])?>
    <div class="tab_staff_content">
        <form action="" class="layui-form">
            <div class="demoTable" style=" text-align: left;margin:10px 0px;">
                <div class="layui-inline">
                    <input class="layui-input" name="keyword" id="demoReload" autocomplete="off" placeholder="请输入手机号">
                </div>
                <button class="layui-btn" data-type="reload" type="submit">搜索</button>
            </div>
        </form>
        <table class="layui-hide" id="offlineTable" lay-filter="offlineTable"></table>
    </div>
</div>

<script type="text/html" id="barDemo">
    {{# if(d.status == 0){ }}
        <a class="layui-btn layui-btn-xs" lay-event="handle">标记处理</a>
    {{# } }}
</script>
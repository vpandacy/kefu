<?php
use www\assets\MerchantAsset;
use common\components\helper\StaticAssetsHelper;
use common\services\GlobalUrlService;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/overall/index/import.js'), MerchantAsset::className());
?>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'settings',
        'current_menu'  =>  'common_words'
    ])?>

    <div class="tab_staff_content">
        <div class="site-text">
            <div class="layui-form-item">
                <div class="layui-upload">
                    <button type="button" class="layui-btn layui-btn-normal" id="testList">选择文件</button>
                    <div type="button" class="layui-btn">
                        <a class="layui-icon layui-icon-dows" href="<?=GlobalUrlService::buildKFStaticUrl('/other/import.xlsx')?>">&#xe601;</a>导入示例下载
                    </div>
                    <div class="layui-upload-list">
                        <table class="layui-table">
                            <thead>
                            <tr><th>文件名</th>
                                <th>大小</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr></thead>
                            <tbody id="demoList"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
            </div>
        </div>
    </div>
</div>
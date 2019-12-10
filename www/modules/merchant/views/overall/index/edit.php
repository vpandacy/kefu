<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFUrl('/js/merchant/overall/index/edit.js'), MerchantAsset::className());
?>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'settings',
        'current_menu'  =>  'common_words'
    ])?>
</div>

<ul class="submenu">
    <form class="layui-form" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">常用语</label>
            <div class="layui-input-block">
                <textarea name="words" placeholder="请输入内容" class="layui-textarea"><?=$words['words']?></textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" name="id" value="<?=$words['id']?>">
                <button class="layui-btn" lay-submit="" lay-filter="commonWords">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</ul>

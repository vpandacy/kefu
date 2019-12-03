<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwUrl('/js/merchant/overall/index/edit.js'), MerchantAsset::className());
?>
<div id="staff_index_index">
    <div class="staff_tab">
        <div class="tab_list tab_active" ><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/index/index');?>">常用语管理</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/clueauto/index');?>">线索自动采集</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/breakauto/index');?>">自动断开</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/company/index');?>">企业设置</a></div>
        <div class="tab_list "><a href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/offline/index');?>">离线表单设置</a></div>
    </div>
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

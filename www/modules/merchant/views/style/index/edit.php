<?php
use www\assets\MerchantAsset;
use common\components\helper\StaticAssetsHelper;
use common\services\GlobalUrlService;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/style/index/edit.js'), MerchantAsset::className());
?>

<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'style',
        'current_menu'  =>  'style'
    ])?>

    <div class="tab_staff_content">
        <div class="site-text">
            <form class="layui-form" method="post">
                <div class="layui-form-item">
                    <label class="layui-form-label">风格名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" value="<?=$group['title']?>" lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">风格简介</label>
                    <div class="layui-input-block">
                        <textarea name="desc" placeholder="请输入风格简介" class="layui-textarea"><?=$group['desc']?></textarea>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="hidden" name="id" value="<?=$group['id']?>">
                        <button class="layui-btn" lay-submit="" lay-filter="groupForm">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


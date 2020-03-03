<?php

use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFUrl('/js/merchant/overall/index/edit.js'),
    MerchantAsset::className());
?>
<div id="staff_index_index">
    <?= $this->renderFile('@www/modules/merchant/views/common/bar_menu.php', [
        'bar_menu' => 'settings',
        'current_menu' => 'common_words'
    ]) ?>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12 layui-col-lg12">
                <div class="layui-card">
                    <div class="layui-card-header" style="text-align: center;">
                        常用语设置
                    </div>
                    <div class="layui-card-body layui-row layui-col-space10">
                        <form class="layui-form" method="post" lay-filter="component-form-element">
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-md12">
                                    <label class="layui-form-label">标题：</label>
                                    <div class="layui-input-block">
                                        <input maxlength="40" type="text" name="title" placeholder="请输入标题" autocomplete="off" class="layui-input" value="<?= $words['title']??'' ?>">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">内容：</label>
                                    <div class="layui-input-block">
                                <textarea maxlength="250" name="words" placeholder="请输入内容"
                                          class="layui-textarea"><?= $words['words']??'' ?></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <input type="hidden" name="id" value="<?= $words['id']??0 ?>">
                                        <button class="layui-btn" lay-submit="" lay-filter="commonWords">保存</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
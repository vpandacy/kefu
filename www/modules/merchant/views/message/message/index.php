<?php

use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;
use \common\components\helper\StaticPluginHelper;
use \common\services\ConstantService;

StaticPluginHelper::setDepend(MerchantAsset::className());
StaticPluginHelper::daterangepicker();
StaticPluginHelper::jqueryUIWidget();
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/message/message/index.js'),
    StaticPluginHelper::getDepend())
?>
<style>
    .screen_message input, .screen_message i {
        pointer-events: none;
    }

    .screen_result {
        position: absolute;
        left: 0;
        top: 42px;
        padding: 5px 0;
        z-index: 899;
        min-width: 105%;
        border: 1px solid #d2d2d2;
        max-height: 300px;
        overflow-y: auto;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .12);
        box-sizing: border-box;
        padding: 15px;
        display: none;
    }

    .layui-table td.long {
        max-width: 1px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

</style>
<div id="message_log">
    <?= $this->renderFile('@www/modules/merchant/views/common/bar_menu.php', [
        'bar_menu' => 'message',
        'current_menu' => 'track'
    ]) ?>
    <div class="guest_message_list">
        <form action="" class="layui-form wrap_search">
            <div class="layui-inline">
                <select name="group_id">
                    <option value="<?= ConstantService::$default_status_neg_99; ?>">请选择风格</option>
                    <?php foreach ($style_map as $_item): ?>
                        <option value="<?= $_item['id'] ?>"
                            <?php if ($_item['id'] == $sc['group_id']): ?> selected <?php endif ?> ><?= $_item['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="layui-inline">
                <select name="staff_id">
                    <option value="0">请选择客服</option>
                    <?php foreach ($staff_map as $_item): ?>
                        <option value="<?= $_item['id'] ?>"
                            <?php if ($_item['id'] == $sc['staff_id']): ?> selected <?php endif ?> ><?= "{$_item['name']}（{$_item['mobile']}）" ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="layui-inline" style="width: 16%;">
                <input type="text" class="layui-input" name="date_range_picker" placeholder="请选择日期~~">
                <input type="hidden" name="date_from" value="<?= $sc["date_from"]; ?>">
                <input type="hidden" name="date_to" value="<?= $sc["date_to"]; ?>">
            </div>
            <div class="layui-inline">
                <div class="layui-unselect layui-form-select ">
                    <div class="layui-select-title screen_message">
                        <input type="text" class="layui-input layui-unselect" value="筛选" readonly>
                        <i class="layui-edge"></i>
                    </div>
                    <div class="screen_result" id="screen_result">
                        <input type="checkbox" name="has_talked" value="<?=ConstantService::$default_status_true;?>" title="访客有说话" lay-skin="primary" <?php if( $sc['has_talked'] == ConstantService::$default_status_true ):?> checked="checked" <?php endif;?>>
                        <input type="checkbox" name="has_talked" value="<?=ConstantService::$default_status_false;?>" title="访客没说话" lay-skin="primary" <?php if( $sc['has_talked'] == ConstantService::$default_status_false ):?> checked="checked" <?php endif;?>><br>
                        <input type="checkbox" name="has_mobile" value="<?=ConstantService::$default_status_true;?>" title="手机" lay-skin="primary" <?php if( $sc['has_mobile'] == ConstantService::$default_status_true ):?> checked="checked" <?php endif;?>>
                        <input type="checkbox" name="has_email" value="<?=ConstantService::$default_status_true;?>" title="邮箱" lay-skin="primary" <?php if( $sc['has_email'] == ConstantService::$default_status_true ):?> checked="checked" <?php endif;?>>
                    </div>
                </div>
            </div>

            <div class="layui-inline" style="width: 16%;">
                <input type="text" class="layui-input" name="kw" value="<?= $sc['kw'] ?>" placeholder="请输入搜索关键词、ip、网址">
            </div>

            <div class="layui-inline">
                <button class="layui-btn" data-type="reload" type="submit">搜索</button>
                <a class="btn-link" style="padding-left: 12px;" href="<?= GlobalUrlService::buildKFMerchantUrl("/message/message/index"); ?>">
                    重置搜索
                </a>
            </div>
        </form>
        <table  class="layui-table layui-table-body" >

            <thead>
            <tr>
                <th width="120">访客名称</th>
                <th width="120">IP</th>
                <th width="80">客服</th>
                <th width="100">风格</th>
                <th width="200">来源&nbsp;&nbsp;<i class="fa fa-question-circle fa-lg tooltip"  title="访客通过各种渠道进入网站，主要包括外部链接、搜索引擎、直接访问"></i></th>
                <th width="200">落地页&nbsp;&nbsp;<i class="fa fa-question-circle fa-lg tooltip"  title="访客的进入网站"></i></th>
                <th width="60">终端</th>
                <th width="70">时长</th>
                <th width="120">IP</th>
                <th width="160">来访时间</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($list): ?>
                <?php foreach ($list as $_item): ?>
                    <tr>
                        <td  title="<?= $_item['uuid']; ?>">
                            <?= $_item['guest_number']; ?>
                        </td>
                        <td><?= $_item['client_ip']; ?></td>
                        <td><?= $_item['staff_info']['name'] ?? '暂无'; ?></td>
                        <td><?= $_item['style_info']['title'] ?? '暂无'; ?></td>
                        <td class="long">
                            <?php if( $_item['referer_url'] ):?>
                            <a class="btn-link tooltip" target="_blank" title="<?= $_item['referer_url']; ?>" href="<?= $_item['referer_url']; ?>">
                                <?= $_item['referer_url']; ?>
                            </a>
                            <?php endif;?>
                        </td>
                        <td class="long">
                            <a class="btn-link tooltip" target="_blank" title="<?= $_item['land_url']; ?>" href="<?= $_item['land_url']; ?>">
                                <?= $_item['land_url']; ?>
                            </a>
                        </td>
                        <td><?= $_item['source_desc']; ?></td>
                        <td><?= $_item['duration_desc']; ?></td>
                        <td><?= $_item['client_ip']; ?></td>
                        <td><?= $_item['created_time']; ?></td>
                        <td>
                            <a class="info btn-link" data-uuid="<?= $_item['uuid']; ?>"
                               href="<?= GlobalUrlService::buildNullUrl(); ?>">
                                查看详情
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <?php
        echo \Yii::$app->view->renderFile("@uc/views/common/pagination.php", [
            'pages' => isset($pages) ? $pages : null,
            'url' => '/merchant/message/message/index',
            'sc' => $sc,
        ]);
        ?>
    </div>
</div>


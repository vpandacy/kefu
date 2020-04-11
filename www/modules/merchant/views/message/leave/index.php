<?php

use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;
use \common\components\helper\StaticPluginHelper;
use \common\services\ConstantService;
use \common\components\helper\DataHelper;

StaticPluginHelper::setDepend(MerchantAsset::className());
StaticPluginHelper::jqueryUIWidget();
StaticPluginHelper::daterangepicker();
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/message/leave/index.js'),
    StaticPluginHelper::getDepend())
?>
<style>
    .layui-table td.long {
        max-width: 1px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

</style>
<div id="leave_message_wrap">
    <?= $this->renderFile('@www/modules/merchant/views/common/bar_menu.php', [
        'bar_menu' => 'message',
        'current_menu' => 'leave'
    ]) ?>
    <div class="leave_message_list">
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
            <div class="layui-inline" style="width: 16%;">
                <input type="text" class="layui-input" name="date_range_picker" placeholder="请选择日期~~">
                <input type="hidden" name="date_from" value="<?= $sc["date_from"]; ?>">
                <input type="hidden" name="date_to" value="<?= $sc["date_to"]; ?>">
            </div>

            <div class="layui-inline">
                <input type="text" class="layui-input" name="kw" value="<?= $sc['kw'] ?>" placeholder="请输入搜索关键词">
            </div>

            <div class="layui-inline">
                <button class="layui-btn" data-type="reload" type="submit">搜索</button>
            </div>
        </form>

        <table class="layui-table">
            <thead>
            <tr>
                <th>风格</th>
                <th width="80">姓名</th>
                <th width="90">手机号</th>
                <th width="80">微信号</th>
                <th>留言信息</th>
                <th width="100">地址</th>
                <th width="120">落地页&nbsp;&nbsp;<i class="fa fa-question-circle fa-lg tooltip"  title="访客的进入网站"></i></th>
                <th width="50">状态</th>
                <th width="120">留言时间</th>
                <th width="80">操作</th>
            </tr>

            </thead>
            <tbody>
            <?php if ($list): ?>
                <?php foreach ($list as $_item): ?>
                    <tr>
                        <td><?=$_item['style_info']['title'] ?? '暂无'; ?></td>
                        <td><?=DataHelper::encode( $_item['name'] );?></td>
                        <td><?=DataHelper::encode( $_item['mobile'] );?></td>
                        <td><?=DataHelper::encode( $_item['wechat'] );?></td>
                        <td><?=DataHelper::encode( $_item['message'] );?></td>
                        <td>
                            <?=DataHelper::encode( $_item['client_ip'] );?><br/>
                            <?=DataHelper::encode( $_item['ip_desc'] );?>
                        </td>
                        <td class="long">

                            <?php if( $_item['land_url'] ):?>
                                <a class="btn-link tooltip" target="_blank" title="<?= $_item['land_url']; ?>" href="<?= $_item['land_url']; ?>">
                                    <?= DataHelper::encode( $_item['land_url'] ); ?>
                                </a>
                            <?php endif;?>
                        </td>
                        <td><?=ConstantService::$common_status_map3[$_item['status'] ] ??"";?></td>
                        <td><?=DataHelper::encode( $_item['created_time'] );?></td>
                        <td>
                            <a class="ops btn-link" data-id="<?= $_item['id']; ?>"
                               href="<?= GlobalUrlService::buildNullUrl(); ?>">
                                 标记已处理
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
            'url' => '/merchant/message/leave/index',
            'sc' => $sc,
        ]);
        ?>
    </div>
</div>

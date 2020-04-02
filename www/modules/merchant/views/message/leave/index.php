<?php

use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;
use \common\components\helper\StaticPluginHelper;
use \common\services\ConstantService;
use \common\components\helper\DataHelper;

StaticPluginHelper::setDepend(MerchantAsset::className());
StaticPluginHelper::daterangepicker();
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/message/leave/index.js'),
    StaticPluginHelper::getDepend())
?>

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

        <table class="layui-hide" lay-filter="leave_message">
            <thead>
            <tr>
                <th lay-data="{ field:'f1',width:120}">风格</th>
                <th lay-data="{ field:'f2',width:80}">姓名</th>
                <th lay-data="{ field:'f3',width:120}">手机号</th>
                <th lay-data="{ field:'f4',width:120}">微信号</th>
                <th lay-data="{ field:'f5'}">留言信息</th>
                <th lay-data="{ field:'f6',width:80}">状态</th>
                <th lay-data="{ field:'f7',width:160}">留言时间</th>
                <th lay-data="{ field:'f8',width:100}">操作</th>
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

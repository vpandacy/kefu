<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;
use  \common\components\helper\DataHelper;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/user/index/index.js'), MerchantAsset::className())
?>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'member',
        'current_menu'  =>  'member'
    ])?>
    <div class="staff_list_wrap">
        <form action="" class="layui-form wrap_search">
            <div class="layui-inline">
                <input type="text" class="layui-input" name="kw" value="<?=$sc['kw'];?>" placeholder="请输入手机、姓名">
            </div>

            <div class="layui-inline">
                <button class="layui-btn" data-type="reload" type="submit">搜索</button>
                <a class="btn-link" style="padding-left: 12px;" href="<?= GlobalUrlService::buildKFMerchantUrl("/user/index/index"); ?>">
                    重置搜索
                </a>
            </div>
        </form>
        <table class="layui-hide"  lay-filter="staff_list">
            <thead>
            <tr>
                <th lay-data="{ field:'f1',width:60}">ID</th>
                <th lay-data="{ field:'f2',width:120}">姓名</th>
                <th lay-data="{ field:'f3',width:120}">手机号</th>
                <th lay-data="{ field:'f4',width:150}">邮箱</th>
                <th lay-data="{ field:'f5',width:120}">QQ号码</th>
                <th lay-data="{ field:'f6',width:120}">微信号</th>
                <th lay-data="{ field:'f7'}">注册IP</th>
                <th lay-data="{ field:'f8',width:120}">来源</th>
                <th lay-data="{ field:'f9',width:160}">创建时间</th>
                <th lay-data="{ field:'f10',width:120}">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($list): ?>
                <?php foreach ($list as $_item): ?>
                    <tr>
                        <td>
                            <?= $_item['id']; ?>
                        </td>
                        <td><?= DataHelper::encode( $_item['name'] ); ?></td>
                        <td><?= DataHelper::encode( $_item['mobile'] ); ?></td>
                        <td><?= DataHelper::encode( $_item['email'] ); ?></td>
                        <td><?= DataHelper::encode( $_item['qq'] ); ?></td>
                        <td><?= DataHelper::encode( $_item['wechat'] ); ?></td>
                        <td><?= DataHelper::encode( $_item['reg_ip'] ); ?></td>
                        <td><?= DataHelper::encode( $_item['source_desc'] ); ?></td>
                        <td><?= $_item['created_time']; ?></td>
                        <td>
                            <a class="info btn-link"  href="<?= GlobalUrlService::buildKFMerchantUrl("/user/index/edit",[ "member_id" => $_item['id'] ]); ?>">
                                编辑
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
            'url' => '/merchant/user/index/index',
            'sc' => $sc,
        ]);
        ?>
    </div>
</div>
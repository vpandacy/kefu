<?php
use common\components\helper\StaticAssetsHelper;
use common\services\GlobalUrlService;
use uc\assets\UcAsset;
use \common\components\helper\StaticPluginHelper;
use \common\services\ConstantService;

StaticPluginHelper::setDepend(UcAsset::className());
StaticAssetsHelper::includeAppJsStatic( GlobalUrlService::buildUcStaticUrl("/js/staff/index.js"),StaticPluginHelper::getDepend() )
?>
<style>
    .layui-table-tool-temp {
        padding-right: 0px !important;
    }
    .layui-table-tool .layui-btn-container {
        display: flex;
        justify-content: space-between;
    }
</style>

<div id="staff_index_wrap">
    <?=$this->renderFile('@uc/views/common/bar_menu.php',[
        'bar_menu'  =>  'user',
        'current_menu'  =>  'sub_user'
    ])?>

    <div class="staff_list">
        <form action="" class="layui-form wrap_search">
            <div class="layui-inline">
                <select name="group_id">
                    <option value="<?= ConstantService::$default_status_false; ?>">请选择部门</option>
                    <?php foreach ($department_list as $_item): ?>
                        <option value="<?= $_item['id'] ?>"
                            <?php if ($_item['id'] == $sc['department_id']): ?> selected <?php endif ?> ><?= $_item['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="layui-inline" style="width: 16%;">
                <input type="text" class="layui-input" name="kw" value="<?= $sc['kw'] ?>" placeholder="请输入姓名/手机/邮箱">
            </div>

            <div class="layui-inline">
                <button class="layui-btn" data-type="reload" type="submit">搜索</button>
            </div>
            <div class="layui-inline pull-right">
                <button class="layui-btn">+员工</button>
            </div>
        </form>
        <table class="layui-hide"  lay-filter="staff_list">
            <thead>
            <tr>
                <th lay-data="{ field:'f1'}">姓名</th>
                <th lay-data="{ field:'f2'}">昵称</th>
                <th lay-data="{ field:'f3',width:180}">邮箱</th>
                <th lay-data="{ field:'f4',width:150}">手机号</th>
                <th lay-data="{ field:'f5'}">所属部门</th>
                <th lay-data="{ field:'f6',width:120}">接听数</th>
                <th lay-data="{ field:'f7',width:80}">状态</th>
                <th lay-data="{ field:'f8',width:120}">创建时间</th>
                <th lay-data="{ field:'f9',width:100}">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($list): ?>
                <?php foreach ($list as $_item): ?>
                    <tr>
                        <td><?= $_item['name']; ?></td>
                        <td><?= $_item['nickname']; ?></td>
                        <td><?= $_item['email']; ?></td>
                        <td><?= $_item['mobile']; ?></td>
                        <td><?= $_item['depart_info']['name']??''; ?></td>
                        <td><?= $_item['listen_nums']; ?></td>
                        <td>
                            <span class="layui-btn layui-btn-radius layui-btn-danger">
                                <?= ConstantService::$common_status_mapping2[$_item['status']]??''; ?>
                            </span>

                        </td>
                        <td><?= $_item['created_time']; ?></td>
                        <td>
                            <a class="info btn-link" href="<?= GlobalUrlService::buildNullUrl(); ?>">
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
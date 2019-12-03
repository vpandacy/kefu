<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/merchant/staff/action/index.js'), MerchantAsset::className());
?>
<style>
    .layui-table tbody tr:hover,.layui-table-hover {
        background-color: white;
    }
    tbody tr:first-child td:first-child{
        border-top: none;
    }

    tbody tr:first-child td:last-child{
        border-top: none;
    }
</style>
<div id="staff_index_index">
    <div class="staff_tab">
        <div class="tab_list">
            <a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/index/index');?>">子账号管理</a>
        </div>
        <div class="tab_list ">
            <a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/department/index');?>">部门管理</a>
        </div>
        <div class="tab_list">
            <a href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/role/index');?>">角色管理</a>
        </div>
        <div class="tab_list tab_active">
            <a href="<?=GlobalUrlService::buildMerchantUrl('/staff/action/index')?>">权限管理</a>
        </div>
    </div>

    <div class="tab_staff_content">
        <div class="layui-table-header">
            <form class="layui-form" action="">
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>一级模块</th>
                        <th>二级模块</th>
                        <th>权限</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($permissions as $level_name => $permission):?>
                        <tr>
                            <td><?=$level_name?></td>
                            <td colspan="2" style="padding: 0">
                            <table style="margin: 0;width: 100%;">
                                <?php foreach ($permission['child'] as $second_levels):?>
                                    <?php foreach ($second_levels['acts'] as $level_3 => $action): ?>
                                        <tr>
                                            <?php if( $level_3 == 0 ):?>
                                                <td style="width: 50%;border-left: none;" rowspan="<?=$second_levels["counter"];?>">
                                                    <?=$second_levels["name"]; ?>
                                                </td>
                                            <?php endif;?>
                                            <td>
                                                <input type="checkbox" name="permissions[]" title="<?= $action['name']; ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach;?>
                            </table>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            </form>
        </div>
    </div>


</div>

<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm" lay-event="getCheckData">添加</button>
        <button class="layui-btn layui-btn-sm" lay-event="isAll">恢复</button>
    </div>
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>


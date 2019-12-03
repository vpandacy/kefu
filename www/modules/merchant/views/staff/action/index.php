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

    <form class="layui-form" action="">
        <div class="layui-card">
            <div class="layui-card-header">选择角色</div>
            <div class="layui-card-body">
                <div class="layui-form-item">
                    <div class="layui-input-inline" style="width: 100%">
                        <?php foreach($roles as $role):?>
                            <input type="radio" lay-filter="choice" name="role_id" value="<?=$role['id']?>" title="<?=$role['name']?>">
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-card">
            <div class="layui-card-header">权限列表</div>
            <div class="layui-card-body">
                <!-- 这里是表格... -->
                <div class="tab_staff_content">
                    <div class="layui-table-header">
                        <table class="layui-table">
                            <thead>
                                <tr>
                                    <th>一级模块</th>
                                    <th>二级模块</th>
                                    <th>权限</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if($permissions):?>
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
                                                                <div class="layui-form-inline">
                                                                    <input type="checkbox" value="<?=$action['id']?>" class="action" name="permissions[]" title="<?= $action['name']; ?>">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endforeach;?>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php else:?>
                                <tr>
                                    <td colspan="3" class="centered">暂无权限设置,请联系管理员</td>
                                </tr>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-form-item" style="text-align: center">
            <button type="button" lay-submit lay-filter="*" class="layui-btn layui-btn-fluid">保  存</button>
        </div>
    </form>
</div>
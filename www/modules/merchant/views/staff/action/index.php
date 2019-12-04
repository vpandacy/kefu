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

    table table tr:nth-last-child(2) td{
        border-bottom: none;
    }

    table table tr:last-child td{
        border-bottom: none;
    }

    table table td{
        border-right: none !important;
    }

    .layui-table>tbody>tr:last-child td{
        border-bottom: none;
    }
</style>

<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'user',
        'current_menu'  =>  'action'
    ])?>

    <form class="layui-form">
        <fieldset>
            <legend>
                <a name="use">权限列表</a>
            </legend>
        </fieldset>
        <div class="layui-card">
            <div style="background: #F2F2F2; padding: 10px 0 10px 10px;">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <select name="role_id" lay-filter="choice" lay-verify="required" lay-search="">
                            <option value="0">选择角色</option>
                            <?php foreach($roles as $role):?>
                                <option value="<?=$role['id']?>"><?=$role['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
                <!-- 这里是表格... -->
                <div class="tab_staff_content">
                    <div class="layui-table-header">
                        <table class="layui-table" style="margin: 0;">
                            <thead>
                                <tr>
                                    <th width="20%">一级模块</th>
                                    <th width="40%">二级模块</th>
                                    <th width="40%">权限</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if($permissions):?>
                                <?php foreach($permissions as $level_name => $permission):?>
                                    <tr>
                                        <td width="20%"><?=$level_name?></td>
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


        <div class="layui-form-item" style="text-align: center; padding: 0 15px;">
            <button type="button" lay-submit lay-filter="*" class="layui-btn layui-btn-fluid">保  存</button>
        </div>
    </form>
</div>
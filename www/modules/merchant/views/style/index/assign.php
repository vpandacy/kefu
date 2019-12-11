<?php
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;
use common\services\GlobalUrlService;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/style/index/assign.js'), MerchantAsset::className());
?>
<?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
    'bar_menu'  =>  'style',
    'current_menu'  =>  'style'
])?>

<div class="tab_staff_content">
    <fieldset>
        <legend>
            <a name="use"><?=$group['title']?></a>
        </legend>
    </fieldset>
    <form action="" class="layui-form">
        <div class="site-text">
            <?php foreach ($data as $department_staff):?>
            <fieldset>
                <legend>
                    <a name="use"><?=$department_staff[0]['department']?></a>
                </legend>
            </fieldset>
            <div>
                <div class="layui-input-inline">
                    <?php foreach($department_staff as $staff):?>
                        <input type="checkbox" <?=in_array($staff['id'], $staff_ids) ? 'checked' : ''?> name="staff_id[]" value="<?=$staff['id']?>" title="<?=$staff['name']?>">
                    <?php endforeach;?>
                </div>
            </div>
            <?php endforeach;?>
        </div>

        <div class="layui-form-item" style="text-align: center; margin-top: 15px;">
            <input type="hidden" name="group_id" value="<?=$group['id']?>">
            <button type="button" lay-submit lay-filter="*" class="layui-btn layui-btn-fluid">保  存</button>
        </div>
    </form>
</div>
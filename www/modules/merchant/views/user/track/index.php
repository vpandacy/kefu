<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/user/track/index.js'), MerchantAsset::className())
?>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'message',
        'current_menu'  =>  'track'
    ])?>
    <div class="tab_staff_content">
        <form action="" class="layui-form">
            <div class="demoTable" style=" text-align: left;margin:10px 0px;">
                <div class="layui-inline">
                    <select name="group_id">
                        <option value="0">所有风格</option>
                        <?php foreach($groups as $group):?>
                            <option value="<?=$group['id']?>" <?=$group['id'] == $search_conditions['group_id'] ? 'selected' : ''?> ><?=$group['title']?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="layui-inline">
                    <input type="text" class="layui-input" id="time" name="time" value="<?=$search_conditions['time']?>" placeholder="请选择时间">
                </div>

                <button class="layui-btn" data-type="reload" type="submit">搜索</button>
            </div>
        </form>
        <table class="layui-hide" id="trackTable" lay-filter="trackTable"></table>
    </div>
</div>
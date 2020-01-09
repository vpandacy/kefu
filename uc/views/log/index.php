<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use uc\assets\UcAsset;


StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildUcStaticUrl('/js/log/index.js'), UcAsset::className())
?>
<div id="staff_index_index">
    <?=$this->renderFile('@uc/views/common/bar_menu.php',[
        'bar_menu'  =>  'user',
        'current_menu'  =>  'staff_log'
    ])?>
    <div class="tab_staff_content">
        <form action="" class="layui-form">
            <div class="demoTable" style=" text-align: left;margin:10px 0px;">
                <div class="layui-inline">
                    <select name="staff_id" id="">
                        <option value="0">请选择客服</option>
                        <?php foreach($staffs as $staff):?>
                            <option value="<?=$staff['id']?>" <?=$search_conditions['staff_id'] == $staff['id'] ? 'selected' : '' ?>><?=$staff['name']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <button class="layui-btn" data-type="reload" type="submit">搜索</button>
            </div>
        </form>


        <table class="layui-hide" lay-filter="logTable" id="logTable" style="position: relative">
        </table>
    </div>
</div>

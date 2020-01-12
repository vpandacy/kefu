<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/user/track/index.js'), MerchantAsset::className())
?>
<style>
    .layui-table-body {
        overflow-x: hidden;
    }
</style>
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
                        <option value="0">默认风格</option>
                        <?php foreach($groups as $group):?>
                            <option value="<?=$group['id']?>" <?=$group['id'] == $search_conditions['group_id'] ? 'selected' : ''?> ><?=$group['title']?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="layui-inline">
                    <input type="text" class="layui-input" id="time" name="time" value="<?=$search_conditions['time']?>" placeholder="请选择时间">
                </div>

                <div class="layui-inline">
                    <input type="text" class="layui-input" id="mobile" name="mobile" value="<?=$search_conditions['mobile']?>" placeholder="请输入手机号">
                </div>

                <div class="layui-inline">
                    <input type="text" class="layui-input" id="url" name="url" value="<?=$search_conditions['url']?>" placeholder="请输入网址">
                </div>

                <div class="layui-inline">
                    <select name="staff_id" id="">
                        <option value="0">请选择客服</option>
                        <?php foreach($staffs as $staff):?>
                            <option value="<?=$staff['id']?>" <?=$search_conditions['staff_id'] == $staff['id'] ? 'selected' : ''?>><?="{$staff['name']}[{$staff['mobile']}]"?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="layui-inline">
                    <input type="text" class="layui-input" id="qq" name="qq" value="<?=$search_conditions['qq']?>" placeholder="请输入QQ号码">
                </div>

                <div class="layui-inline">
                    <input type="text" class="layui-input" id="email" name="email" value="<?=$search_conditions['email']?>" placeholder="请输入邮箱">
                </div>

                <div class="layui-inline">
                    <input type="text" class="layui-input" id="wechat" name="wechat" value="<?=$search_conditions['wechat']?>" placeholder="请输入微信号码">
                </div>

                <button class="layui-btn" data-type="reload" type="submit">搜索</button>
            </div>
        </form>
        <table class="layui-hide" id="trackTable" lay-filter="trackTable">

        </table>
    </div>
</div>

<script type="text/html" id="trackTool">
    <a class="layui-btn layui-btn-xs" lay-event="see">查看详情</a>
</script>
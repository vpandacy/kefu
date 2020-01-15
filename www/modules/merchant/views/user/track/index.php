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
    .layui-edge-active{
        margin-top: -9px !important;
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
        margin-top: -3px\9;
        /*margin-top: -9px\0/IE9;*/
    }
    .screen_result {
        position: absolute;
        left: 0;
        top: 42px;
        padding: 5px 0;
        z-index: 899;
        min-width: 105%;
        border: 1px solid #d2d2d2;
        max-height: 300px;
        overflow-y: auto;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 4px rgba(0,0,0,.12);
        box-sizing: border-box;
        padding: 15px;
        display: none;
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
                    <input type="text" class="layui-input" style="width: 280px" id="time" name="time" value="<?=$search_conditions['time']?>" placeholder="请选择时间">
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
<!---->
<!--                <div class="layui-inline">-->
<!--                    <input type="text" class="layui-input" id="qq" name="qq" value="--><?//=$search_conditions['qq']?><!--" placeholder="请输入QQ号码">-->
<!--                </div>-->
<!---->
<!--                <div class="layui-inline">-->
<!--                    <input type="text" class="layui-input" id="email" name="email" value="--><?//=$search_conditions['email']?><!--" placeholder="请输入邮箱">-->
<!--                </div>-->
<!---->
<!--                <div class="layui-inline">-->
<!--                    <input type="text" class="layui-input" id="wechat" name="wechat" value="--><?//=$search_conditions['wechat']?><!--" placeholder="请输入微信号码">-->
<!--                </div>-->

                <div class="layui-inline">
                    <div class="layui-unselect layui-form-select ">
                        <div class="layui-select-title screen_message">
                            <input type="text" class="layui-input layui-unselect" value="筛选"  readonly>
                            <i class="layui-edge"></i>
                        </div>
                        <div class="screen_result">
                            <input type="checkbox" name="" title="访客有说话" lay-skin="primary" checked>
                            <input type="checkbox" name="" title="访客没说话" lay-skin="primary" checked><br>
<!--                            <input type="checkbox" name="" title="对话线索" lay-skin="primary" checked><br>-->
                            <input type="checkbox" name="" title="手机" lay-skin="primary" checked>
                            <input type="checkbox" name="" title="QQ" lay-skin="primary" checked>
                            <input type="checkbox" name="" title="邮箱" lay-skin="primary" checked>
                            <input type="checkbox" name="" title="固话" lay-skin="primary" checked>
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                <button class="layui-btn" data-type="reload" type="submit">搜索</button>
                </div>
            </div>
        </form>
        <table class="layui-hide" id="trackTable" lay-filter="trackTable">

        </table>
    </div>
</div>

<script type="text/html" id="trackTool">
    <a class="layui-btn layui-btn-xs" lay-event="see">查看详情</a>
</script>
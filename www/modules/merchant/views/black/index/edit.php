<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic( GlobalUrlService::buildKFStaticUrl('/js/merchant/black/index/edit.js'), MerchantAsset::className() )
?>

<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'blacklist',
        'current_menu'  =>  'blacklist'
    ])?>

    <div class="tab_staff_content">
        <div class="site-text">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">IP地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="ip" required="" lay-verify="required" placeholder="请输入IP地址" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">游客编号</label>
                    <div class="layui-input-block">
                        <input type="text" name="uuid" required="" lay-verify="required" placeholder="请输入游客编号" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">接待客服</label>
                    <div class="layui-input-block">
                        <select name="staff_id" lay-verify="required" class="layui-form-select">
                            <option value="0">请选择客服</option>
                            <?php foreach($staff as $employee):?>
                                <option value="<?=$employee['id']?>"><?=$employee['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">过期时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="expired_time" id="expired_time" required="" lay-verify="required" placeholder="请选择过期时间" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit="" lay-filter="black">立即提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



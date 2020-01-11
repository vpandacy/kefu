<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/user/index/edit.js'), MerchantAsset::className());
?>
<style>
    .layui-input-block {
        width: 200px;
    }
</style>
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'member',
        'current_menu'  =>  'member'
    ])?>
    <div class="tab_staff_content">
        <div class="site-text">
            <form class="layui-form" method="post">
                <div class="layui-form-item">
                    <label class="layui-form-label">姓名</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" value="<?=$member['name']?>" placeholder="请输入姓名" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">手机号</label>
                    <div class="layui-input-block">
                        <input type="text" name="mobile" value="<?=$member['mobile']?>" placeholder="请输入手机号" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-block">
                        <input type="text" name="email" value="<?=$member['email']?>" placeholder="请输入邮件地址" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">QQ号码</label>
                    <div class="layui-input-block">
                        <input type="text" name="qq" value="<?=$member['qq']?>" placeholder="请输入QQ号码" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">微信号</label>
                    <div class="layui-input-block">
                        <input type="text" name="wechat" value="<?=$member['wechat']?>" placeholder="请输入微信号" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-form-item">
                        <label class="layui-form-label">省份</label>
                        <div class="layui-input-block">
                            <select name="province_id" lay-filter="province">
                                <?php foreach($provinces as $province_id => $province):?>
                                    <option value="<?=$province_id?>" <?=$province_id == $current_province ? 'selected' : ''?>><?=$province?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-form-item">
                        <label class="layui-form-label">城市</label>
                        <div class="layui-input-block">
                            <select name="city_id">
                                <?php foreach($cities as $city):?>
                                    <option value="<?=$city['id']?>" <?=$city['id'] == $member['city_id'] ? 'selected' : ''?>><?=$city['name']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">备注</label>
                    <div class="layui-input-block">
                        <textarea name="desc" class="layui-textarea" cols="30" rows="10"><?=$member['desc']?></textarea>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="hidden" name="id" value="<?=$member['id']?>">
                        <button class="layui-btn" lay-submit="" lay-filter="userFrom">保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

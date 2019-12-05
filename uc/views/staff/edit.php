<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/plugins/qiniu/plupload/moxie.min.js'), MerchantAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/plugins/qiniu/plupload/plupload.full.min.js'), MerchantAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/plugins/qiniu/plupload/zh_CN.js'), MerchantAsset::className());

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/plugins/qiniu/qiniu.min.js'), MerchantAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildUcStaticUrl('/js/staff/edit.js'), MerchantAsset::className());
?>
<div id="staff_index_index">
    <?=$this->renderFile('@uc/views/common/bar_menu.php',[
        'bar_menu'  =>  'user',
        'current_menu'  =>  'sub_user'
    ])?>
    <div class="tab_staff_content">
        <table class="layui-hide" id="test" style="position: relative">
        </table>
    </div>
</div>

<ul class="submenu">
    <div class="site-text">
    <form class="layui-form" method="post">
        <div class="layui-form-item">
            <label class="layui-form-label">姓名/商户名</label>
            <div class="layui-input-block">
                <input type="text" name="name" value="<?=$staff['name']?>" lay-verify="required" placeholder="请输入姓名或商户名" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-block">
                <input type="text" name="email" value="<?=$staff['email']?>" lay-verify="required" placeholder="请输入邮件地址" <?=$staff['email'] ? 'disabled' : ''?> autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-block">
                <input type="text" name="mobile" value="<?=$staff['mobile']?>" lay-verify="required|phone" placeholder="请输入手机号" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">接听数</label>
            <div class="layui-input-block">
                <input type="text" name="listen_nums" value="<?=$staff['listen_nums']?>" lay-verify="required" placeholder="请输入接听数量,0代表没有上限" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">请选择部门</label>
            <div class="layui-input-block">
                <select name="department_id" lay-verify="required">
                    <option value="0">请选择部门</option>
                    <?php foreach($departments as $department):?>
                        <option value="<?=$department['id']?>" <?=$department['id'] == $staff['department_id'] ? 'selected' : ''?> ><?=$department['name']?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">请选择角色</label>
            <div class="layui-input-block">
                <?php foreach($roles as $role):?>
                    <input type="checkbox" name="role_ids" <?=in_array($role['id'], $role_ids) ? 'checked' : ''?> value="<?=$role['id']?>" title="<?=$role['name']?>">
                <?php endforeach;?>
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">请上传头像</label>
            <div class="layui-input-block" id="upload_container">
                <button class="layui-btn" id="upload" type="button">
                    <i class="layui-icon"></i>上传头像
                </button>
                <input type="hidden" name="avatar" value="<?=$staff['avatar']?>">
            </div>
            <div class="img-wrapper" style="margin-top: 10px;">
                <?php if($staff['avatar']) :?>
                    <div class="layui-input-block">
                        <img width="100" height="100" src="<?=GlobalUrlService::buildPicStaticUrl('hsh', $staff['avatar'])?>" alt="">
                    </div>
                <?php endif;?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">密码框</label>
            <div class="layui-input-inline">
                <input type="password" name="password" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">如需修改密码请输入</div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input type="password" name="confirm_password" placeholder="请输入确认密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">如需修改密码请输入</div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" name="id" value="<?=$staff['id']?>">
                <button class="layui-btn" lay-submit="" lay-filter="staffFrom">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </div>
    </form>
</ul>

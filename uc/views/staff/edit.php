<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use common\components\helper\StaticPluginHelper;
use uc\assets\UcAsset;

StaticPluginHelper::setDepend(UcAsset::className());
StaticPluginHelper::qiniuPlugin();

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/layui/layui_extends/multiSelect.js'), UcAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildUcStaticUrl('/js/staff/edit.js'), UcAsset::className());
?>
<style>
    .upload_container {
        position: absolute;
        border: 1px solid #e6e6e6;
        top: 20px;
        width: 180px;
        left: 30%;
        height: 170px;
    }
    .upload_container .icon-jiahao1 {
        color: rgba(1, 170, 237, 1);
        font-size: 40px;
    }
    .upload_container .layui-upload-list {
        margin: 0 auto;
        text-align: center;
        line-height: 163px;
    }
    .upload_container .layui-upload-list img {
        width: 180px;
        height: 170px;
    }
    .upload_container .upload_but {
        width: 100%;
    }
    .layui-form-item .layui-form-checkbox[lay-skin=primary] {
        margin-top: 0 !important;
    }
</style>
<div id="staff_index_index">
    <?=$this->renderFile('@uc/views/common/bar_menu.php',[
        'bar_menu'  =>  'user',
        'current_menu'  =>  'sub_user'
    ])?>
    <div class="tab_staff_content">
        <div class="site-text">
            <form class="layui-form" method="post">
                <div class="layui-form-item">
                    <label class="layui-form-label">商户/姓名</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" value="<?=$staff['name']?>" lay-verify="required" placeholder="请输入姓名或商户名" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">昵称</label>
                    <div class="layui-input-block">
                        <input type="text" name="nickname" value="<?=$staff['nickname']?>" title="用于聊天时展示的信息" lay-verify="required" placeholder="请输入昵称" autocomplete="off" class="layui-input">
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
                        <input type="text" name="listen_nums" value="<?=$staff['listen_nums']?>" lay-verify="required" title="0代表没有上限" placeholder="请输入接听数量" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">请选择部门</label>
                    <div class="layui-inline">
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
                    <div class="layui-inline">

                        <select multiple="multiple" lay-filter="test">
                            <?php foreach($roles as $role):?>
                            <option <?=in_array($role['id'], $role_ids) ? 'selected' : ''?> name="role_ids" value="<?=$role['id']?>" title="<?=$role['name']?>"><?=$role['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>

                <div class="layui-form-item upload_container" id="upload_container">
<!--                    <label class="layui-form-label">请上传头像</label>-->
                    <div class="layui-upload">
                        <div class="layui-upload-list">
                            <img  src="<?=GlobalUrlService::buildPicStaticUrl('hsh', $staff['avatar'])?>" alt="">
                        </div>
                        <button type="button" class="layui-btn upload_but" id="upload" >上传头像</button>
                        <input type="hidden" name="avatar" value="<?=$staff['avatar']?>">
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
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

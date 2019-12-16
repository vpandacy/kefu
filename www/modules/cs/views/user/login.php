<?php
use common\components\helper\StaticAssetsHelper;
use common\services\GlobalUrlService;
use www\assets\CsAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildUcUrl('/js/common/core.js'),CsAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildStaticUrl('/layui/v2.5/layui.all.js'),CsAsset::className());
StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildKFStaticUrl('/css/cs/user/login.css'), CsAsset::className());
StaticAssetsHelper::includeAppCssStatic(GlobalUrlService::buildKFStaticUrl('/css/cs/user/typeface/typeface.css'), CsAsset::className());
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFCSStaticUrl('/js/cs/user/login.js'),CsAsset::className());

?>
<div id="index_login">
    <div class="login_content dflex">
        <div class="login_left_back">
            <img class="login_left_img_one" src="<?=GlobalUrlService::buildKFCSStaticUrl('/images/cs/user/bg2.png')?>">
            <img class="login_left_img_two" src="<?=GlobalUrlService::buildKFCSStaticUrl('/images/cs/user/bg1.png')?>">
            <img class="login_left_img_three" src="<?=GlobalUrlService::buildKFCSStaticUrl('/images/cs/user/bg3.png')?>">
        </div>
        <div class="login_right_login dflex">
            <img  class="login_right_img_Four" src="<?=GlobalUrlService::buildKFCSStaticUrl('/images/cs/user/bg4.png')?>">
            <div class="login_content_sr">
                <!--登录-->
                <div class="login_content_tab sign-in-container">
                    <span class="web-font welcome_title">欢迎登录</span><br>
                    <span class="web-font welcome_tip">请使用您本人的账号密码</span>
                    <input class="login_inp_name" name="account" placeholder="请输入邮箱或手机号">
                    <input class="login_inp_password" type="password" name="password"  placeholder="请输入密码">
                    <div class="login_button cupointer login">登录</div>
                    <div class="textAlign cupointer">
                        <span class="web-font login_register_hand" onclick="loginActive('login')">没有账号？去注册</span>
                    </div>
                </div>

                <!--注册-->
                <div class="register_content_tab sign-up-container">
                    <span class="web-font welcome_title">欢迎注册</span><br>
                    <span class="web-font welcome_tip"></span>
                    <input class="login_inp_name" type="text" name="name"  placeholder="请输入商户名">
                    <input class="login_inp_name" style="margin-top: 0;" type="text" name="email" placeholder="请输入邮箱">
                    <input class="login_inp_password" type="password" name="password"  placeholder="请输入密码">
                    <span class="web-font welcome_password_hand cupointer"></span>
                    <div class="login_button cupointer register" >注册</div>
                    <div class="textAlign cupointer">
                        <span class="web-font login_register_hand" onclick="loginActive('register')">已有账号？去登录</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div style="display: none;" class="hidden_val_wrap">
    <input name="domain_app" value="<?=GlobalUrlService::buildKFCSUrl('');?>"/>
    <input name="domain_uc" value="<?=GlobalUrlService::buildUCUrl('');?>"/>
</div>
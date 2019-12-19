<?php
use common\components\helper\StaticAssetsHelper;
use common\services\GlobalUrlService;
use uc\assets\UcAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildUcStaticUrl('/js/user/login.js'),UcAsset::className());
?>

<div id="index_login">
    <div class="login_content dflex">
        <div class="login_left_back">
            <img class="login_left_img_one" src="<?=GlobalUrlService::buildUcUrl('/images/user/bg2.png')?>">
            <img class="login_left_img_two" src="<?=GlobalUrlService::buildUcUrl('/images/user/bg1.png')?>">
            <img class="login_left_img_three" src="<?=GlobalUrlService::buildUcUrl('/images/user/bg3.png')?>">
        </div>
        <div class="login_right_login dflex">
            <img  class="login_right_img_Four" src="<?=GlobalUrlService::buildUcUrl('/images/user/bg4.png')?>">
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
                <form class="layui-form" action="">
                <div class="register_content_tab sign-up-container">
                    <span class="web-font welcome_title">欢迎注册</span><br>
                    <span class="web-font welcome_tip"></span>
                    <input class="login_inp_name" type="text" name="merchant_name"  placeholder="请输入商户名">
                    <input class="login_inp_name" style="margin-top: 0;" type="text" name="account" placeholder="请输入手机号">
                    <div class="po-relative">
                        <input class="login_inp_name " style="margin-top: 0;" type="text" name="img_captcha" placeholder="请输入验证码">
                        <div class="graphic_code cupointer_img">
                            <img  class="cupointer_img" onclick="this.src='<?=GlobalUrlService::buildUcUrl('/user/captcha')?>' + '?time='+ Math.random()" src="<?=GlobalUrlService::buildUcUrl('/user/captcha')?>">
                        </div>
                    </div>
                    <div class="po-relative">
                        <input class="login_inp_name" style="margin-top: 0;" type="text" name="captcha" placeholder="请输入手机验证码">
                        <div onclick="uc_user_login_ops.sendemail()" class="iphone_code cupointer">获取验证码</div>
                    </div>
                    <input class="login_inp_password" type="password" name="password"  placeholder="请输入密码">
                    <span class="web-font welcome_password_hand cupointer"></span>
                    <div class="login_button cupointer register" >注册</div>
                    <div class="textAlign cupointer">
                        <span class="web-font login_register_hand" onclick="loginActive('register')">已有账号？去登录</span>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
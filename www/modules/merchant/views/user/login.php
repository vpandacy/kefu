<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl("/js/merchant/user/login.js"),MerchantAsset::className());
?>
<div class="dowebok" id="dowebok">
    <div class="form-container sign-up-container">
        <form action="#">
            <h1>注册</h1>
            <div class="social-container">
            </div>
            <input type="text" placeholder="姓名">
            <input type="text" name="mobile" placeholder="请输入手机号">
            <input type="password" placeholder="密码">
            <button type="button">注册</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form action="#">
            <h1>登录</h1>
            <div class="social-container">
            </div>
            <input type="text" name="mobile" placeholder="请输入手机号进行登录">
            <input type="password" name="password" placeholder="密码">
            <a href="#">忘记密码？</a>
            <button type="button" class="login">登录</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>已有帐号？</h1>
                <p>请使用您的帐号进行登录</p>
                <button class="ghost" id="signIn">登录</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>没有帐号？</h1>
                <p>立即注册加入我们，和我们一起开始旅程吧</p>
                <button class="ghost" id="signUp">注册</button>
            </div>
        </div>
    </div>
</div>

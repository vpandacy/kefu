<?php
use \common\services\GlobalUrlService;
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <title>登录</title>
    <link href="<?=GlobalUrlService::buildWwwStaticUrl("/css/cs/login/login.css");?>" rel="stylesheet">
</head>

<body style="background: url('<?=GlobalUrlService::buildWwwStaticUrl("/images/cs/login/bg.jpg");?>') no-repeat 0 0; background-size: cover">
<div class="dowebok" id="dowebok">
    <div class="form-container sign-up-container">
        <form action="#">
            <h1>注册</h1>
            <div class="social-container">
<!--                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>-->
<!--                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>-->
<!--                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>-->
            </div>
<!--            <span>或使用邮箱注册</span>-->
            <input type="text" placeholder="姓名">
            <input type="email" placeholder="电子邮箱">
            <input type="password" placeholder="密码">
            <button>注册</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form action="#">
            <h1>登录</h1>
            <div class="social-container">
<!--                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>-->
<!--                <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>-->
<!--                <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>-->
            </div>
<!--            <span>或使用您的帐号</span>-->
            <input type="email" placeholder="电子邮箱">
            <input type="password" placeholder="密码">
            <a href="#">忘记密码？</a>
            <button>登录</button>
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
<script type="text/javascript" src="<?=GlobalUrlService::buildWwwStaticUrl("/js/cs/login/login.js");?>"></script>
</body>

</html>
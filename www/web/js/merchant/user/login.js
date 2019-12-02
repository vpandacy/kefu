;
var merchant_user_login_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind:function () {
        var signUpButton = document.getElementById('signUp');
        var signInButton = document.getElementById('signIn');
        var container = document.getElementById('dowebok');

        signUpButton.addEventListener('click', function () {
            container.classList.add('right-panel-active')
        });

        signInButton.addEventListener('click', function () {
            container.classList.remove('right-panel-active')
        });

        // 登录.
        $('.login').on('click',function () {
            
        });

        // 注册.
        $('.register').on('click',function () {
            
        });
    }
};


$(document).ready(function () {
    merchant_user_login_ops.init();
});
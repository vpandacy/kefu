;
var countdown=60;
function loginActive() {
    // 登录、找回密码、注册 来回切换
    let loginDom = document.getElementsByClassName('login_content_sr')[0]
    arguments[0] === 'login' ? loginDom.classList.add('right-panel-active') : loginDom.classList.remove('right-panel-active');
    arguments[0] === 'password' ? loginDom.classList.add('right-panel-password-active') : loginDom.classList.remove('right-panel-password-active');
}
function login(account,password) {

}
var merchant_user_login_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind:function () {
        // 登录.
        var url = '';
        $('.login').on('click',function () {
            var account = $('.sign-in-container [name=account]').val(),
                password = $('.sign-in-container [name=password]').val();

            if(!account) {
                return $.msg('请输入正确的登录凭证');
            }

            if(!password || password.length > 255) {
                return $.msg('请填写登录密码');
            }
            var index = $.loading(1,{shade: .5});
            $.ajax({
                type: 'POST',
                url: uc_common_ops.buildUcUrl('/user/login'),
                data: {
                    account: account,
                    password: password
                },
                dataType: 'json',
                success: function ( res ) {
                    $.close(index);
                    var callback = null;
                    if (res.code == 200) {
                        callback = function(){
                            // 弹出选择应用
                            document.getElementsByClassName('login_cover')[0].classList.remove('disnone');
                            document.getElementsByClassName('login_cover_content')[0].classList.remove('disnone');
                            url = res.data.url;
                        };
                    }
                    $.msg(res.msg,res.code == 200, callback);
                },
                error:function () {
                    $.close(index);
                }
            });
        });
        $('.login_applications').on('click',function () {
            window.location.href = url;

        })
        // 注册.
        $('.register').on('click',function () {
            let fromData = ['merchant_name','account','img_captcha','captcha','password']
            let param = {}
            if(!$(".sign-up-container [name='merchant_name']").val()) {
                return $.msg('请填写正确的商户名称');
            }
            if(!$(".sign-up-container [name='account']").val() || $(".sign-up-container [name='account']").val().length != 11) {
                return $.msg('请填写正确的手机号');
            }
            if(!$(".sign-up-container [name='img_captcha']").val()) {
                return $.msg('请填写图形验证码');
            }
            if(!$(".sign-up-container [name='captcha']").val()) {
                return $.msg('请填写手机验证码');
            }
            if(!$(".sign-up-container [name='password']").val() || $(".sign-up-container [name='password']").val().length > 255) {
                return $.msg('请填写登录密码');
            }
            fromData.forEach((value, index, array) => {
                param[value] = $(".sign-up-container [name="+value+"]").val();
            });
            var index = $.loading(1,{shade: .5});
            $.ajax({
                type: 'POST',
                url: uc_common_ops.buildUcUrl('/user/register'),
                data: param,
                dataType: 'json',
                success: function ( res ) {
                    $.close(index);
                    var callback  = null;
                    $.msg(res.msg,res.code == 200, callback);
                },
                error:function () {
                    $.close(index);
                }
            });
        });
    },
    captchaImg: function () {
        $('.cupointer').on('click',function () {});
    },
    captchatAjax: function () {
        var index = $.loading(1,{shade: .5});
        var iphoneDom = $(".iphone_code");
        $.ajax({
            type: 'POST',
            url: uc_common_ops.buildUcUrl('/user/get-captcha'),
            data: {
                mobile: arguments[0],
                code: arguments[1]
            },
            dataType: 'json',
            success: function ( res ) {
                $.close(index);
                var callback  = null;
                if (res.code == 200) {
                    merchant_user_login_ops.captcha(iphoneDom);
                }else  {

                }
                $.msg(res.msg,res.code == 200, callback);
            },
            error:function () {
                $.close(index);
            }
        });
    },
    sendemail: function (){
        let email = $(".sign-up-container [name='account']").val();
        let img_captcha = $(".sign-up-container [name='img_captcha']").val();
        this.captchatAjax(email,img_captcha);
    },
    captcha: function (obj) {
        if (countdown == 0) {
            obj.attr('disabled',false);
            obj.text("获取验证码");
            countdown = 60;
            return;
        } else {
            obj.attr('disabled',true);
            obj.text(countdown + 's')
            countdown--;
        }
        setTimeout(function() {
            merchant_user_login_ops.captcha(obj);
         },1000);
    }
};
$(document).ready(function () {
    merchant_user_login_ops.init();
});
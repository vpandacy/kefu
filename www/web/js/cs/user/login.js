;
function loginActive() {
    // 登录、找回密码、注册 来回切换
    let loginDom = document.getElementsByClassName('login_content_sr')[0]
    arguments[0] === 'login' ? loginDom.classList.add('right-panel-active') : loginDom.classList.remove('right-panel-active');
    arguments[0] === 'password' ? loginDom.classList.add('right-panel-password-active') : loginDom.classList.remove('right-panel-password-active');
}

var merchant_user_login_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind:function () {
        // 登录.
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
                url: cs_common_ops.buildKFCSurl('/user/login'),
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
                            window.location.href = res.data.url;
                        };
                    }
                    $.msg(res.msg,res.code == 200, callback);
                },
                error:function () {
                    $.close(index);
                }
            })
        });

        // 注册.
        $('.register').on('click',function () {
            var email = $('.sign-up-container [name=email]').val(),
                password = $('.sign-up-container [name=password]').val(),
                merchant_name = $('.sign-up-container [name=name]').val();

            if(!email || email.indexOf('@') <= 1) {
                return $.msg('请填写正确的邮箱地址');
            }

            if(!password || password.length > 255) {
                return $.msg('请填写登录密码');
            }

            if(!merchant_name) {
                return $.msg('请填写正确的商户名称');
            }

            var index = $.loading(1,{shade: .5});

            $.ajax({
                type: 'POST',
                url: cs_common_ops.buildKFCSurl('/user/reg'),
                data: {
                    email: email,
                    password: password,
                    merchant_name: merchant_name
                },
                dataType:'json',
                success: function ( res ) {
                    $.close(index);

                    var callback = res.code != 200 ? null : function () {
                        location.href = location.href;
                    };

                    return $.msg(res.msg, res.code == 200 , callback);
                },
                error: function () {
                    $.close(index);
                }
            })
        });
    }
};


$(document).ready(function () {
    merchant_user_login_ops.init();
});
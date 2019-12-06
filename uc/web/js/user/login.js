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
            var email = $('.sign-in-container [name=email]').val(),
                password = $('.sign-in-container [name=password]').val();

            if(!email || email.indexOf('@') <= 1) {
                return $.msg('请填写正确的邮箱地址');
            }

            if(!password || password.length > 255) {
                return $.msg('请填写登录密码');
            }

            var index = $.loading(1,{shade: .5});

            $.ajax({
                type: 'POST',
                url: url_manager.buildUcUrl('/user/sign-in'),
                data: {
                    email: email,
                    password: password
                },
                dataType: 'json',
                success: function (response) {
                    $.close(index);
                    if(response.code != 200) {
                        return $.msg(response.msg);
                    }

                    return $.alert(response.msg, function () {
                        location.href = common_ops.getRequest('redirect_uri', '/')
                    });
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
                url: url_manager.buildUcUrl('/user/register'),
                data: {
                    email: email,
                    password: password,
                    merchant_name: merchant_name
                },
                dataType:'json',
                success: function (response) {
                    $.close(index);
                    if(response.code != 200) {
                        return $.msg(response.msg);
                    }
                    
                    return $.alert(response.msg,function () {
                        location.href = location.href;
                    });
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
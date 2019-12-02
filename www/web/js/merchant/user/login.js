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
            var mobile = $('.sign-in-container [name=mobile]').val(),
                password = $('.sign-in-container [name=password]').val();

            if(!/^1\d{10}$/.test(mobile)) {
                return $.msg('请填写正确的手机号');
            }

            if(!password || password.length > 255) {
                return $.msg('请填写登录密码');
            }

            var index = $.loading(1,{shade: .5});

            $.ajax({
                type: 'POST',
                url: common_ops.buildMerchantUrl('/user/sign-in'),
                data: {
                    mobile: mobile,
                    password: password
                },
                dataType: 'json',
                success: function (response) {
                    $.close(index);
                    if(response.code != 200) {
                        return $.msg(response.msg);
                    }

                    return $.alert(response.msg, function () {
                        location.href = common_ops.getRequest('redirect_uri', common_ops.buildMerchantUrl('/'))
                    });
                },
                error:function () {
                    $.close(index);
                }
            })

        });

        // 注册.
        $('.register').on('click',function () {
            
        });
    }
};


$(document).ready(function () {
    merchant_user_login_ops.init();
});
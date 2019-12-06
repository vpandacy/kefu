var user_center = {
    init: function () {
        var index = $.loading(1,{shade: .5}),
            that = this;
        $.ajax({
            type: 'get',
            url: url_manager.buildUcUrl('/user/center'),
            dataType: 'json',
            success: function (response) {
                $.close(index)
                if(response.code != 200 || $('.right_merchant').length <= 0) {
                    return false;
                }

                $('.right_merchant .right_top').html(response.data.html);

                // 重新注册事件.
                that.eventBind();
            }
        })
    },
    eventBind: function () {
        // 这里是动画效果的转移.
        var timer=null;
        $('.menu_info_link').mouseenter(function () {
            $('.menu_info_edit').show();
        }).mouseleave(function () {
            timer=setTimeout(function () {
                $('.menu_info_edit').hide();
            },2000);
        });


        $('.menu_info_edit').mouseover(function () {
            clearTimeout(timer)
            $(this).show();
        }).mouseout(function () {
            $(this).hide();
        })
    }
};



$(document).ready(function () {
    user_center.init();
});
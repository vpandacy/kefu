var user_center = {
    init: function () {
        var index = $.loading(1,{shade: .5});

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
            }
        })
    }
};



$(document).ready(function () {
    user_center.init();
});
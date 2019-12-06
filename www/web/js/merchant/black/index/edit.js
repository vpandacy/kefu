;

var merchant_black_edit_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        layui.use(['form','laydate'], function () {
            var laydate = layui.laydate,
                form = layui.form;

            form.on('submit(black)', function (data) {
                data = data.field;

                var index = $.loading(1, {shade: .5});
                $.ajax({
                    type: 'POST',
                    url: url_manager.buildMerchantUrl('/black/index/save'),
                    dataType: 'json',
                    data: data,
                    success:function (response) {
                        $.close(index);

                        if(response.code != 200) {
                            return $.msg(response.msg)
                        }

                        index = $.alert(response.msg, function () {
                            history.go(-1);
                            $.close(index);
                        });
                    },
                    error: function () {
                        $.close(index);
                    }
                })
                return false;
            });


            laydate.render({
                elem: '#expired_time',
                type: 'datetime'
            });
        })
    }
};


$(document).ready(function () {
    merchant_black_edit_ops.init();
});
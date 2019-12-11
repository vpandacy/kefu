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
                    url: merchant_common_ops.buildMerchantUrl('/black/index/save'),
                    dataType: 'json',
                    data: data,
                    success:function (res) {
                        $.close(index);

                        var callback = res.code != 200 ? null : function () {
                            history.go(-1);
                        };

                        return $.msg(res.msg, res.code == 200 , callback);
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
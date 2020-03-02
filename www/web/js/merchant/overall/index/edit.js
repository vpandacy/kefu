;
var merchant_overall_index_edit_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(commonWords)', function (data) {
                var index = $.loading(1,{shade: .5});

                data = data.field;

                $.ajax({
                    type: 'post',
                    url: merchant_common_ops.buildMerchantUrl('/overall/index/save'),
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
        });
    }
};


$(document).ready(function () {
    merchant_overall_index_edit_ops.init();
});
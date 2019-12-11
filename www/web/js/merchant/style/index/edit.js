;
var merchant_style_index_edit_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var that = this;
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(groupForm)', function (data) {
                data = data.field;
                // 保存数据
                var index = $.loading(1,{shade: .5});

                $.ajax({
                    type: 'POST',
                    url : merchant_common_ops.buildMerchantUrl('/style/index/save'),
                    data: data,
                    dataType: 'json',
                    success:function (res) {
                        $.close(index);

                        var callback = res.code != 200 ? null : function () {
                            location.href = merchant_common_ops.buildMerchantUrl('/style/index/index');
                        };

                        return $.msg(res.msg, res.code == 200 , callback);
                    },
                    error: function () {
                        $.close(index);
                    }
                });
                return false;
            });
        });
    }
};


$(document).ready(function () {
    merchant_style_index_edit_ops.init();
});
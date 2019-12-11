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
                    success:function (response) {
                        $.close(index);
                        if(response.code != 200) {
                            return $.msg(response.msg);
                        }

                        index = $.alert(response.msg,function () {
                            location.href = merchant_common_ops.buildMerchantUrl('/style/index/index');
                            $.close(index);
                        });
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
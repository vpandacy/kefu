;
var merchant_style_index_assign_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var that = this;
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(*)', function (data) {
                data = data.field;

                var select_staff_ids = [];

                $('input[type=checkbox]:checked').each(function() {
                    select_staff_ids.push($(this).val());
                });

                if(select_staff_ids.length < 1) {
                    return $.msg('请选择正确的员工ID');
                }

                // 保存数据
                var index = $.loading(1,{shade: .5});

                $.ajax({
                    type: 'POST',
                    url : url_manager.buildMerchantUrl('/style/index/distribution'),
                    data: {
                        group_id: data.group_id,
                        staff_ids: select_staff_ids
                    },
                    dataType: 'json',
                    success:function (response) {
                        $.close(index);
                        if(response.code != 200) {
                            return $.msg(response.msg);
                        }

                        index = $.alert(response.msg,function () {
                            location.href = url_manager.buildMerchantUrl('/style/index/index');
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
    merchant_style_index_assign_ops.init();
});
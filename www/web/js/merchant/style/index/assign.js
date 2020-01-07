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
                select_staff_ids = $.grep(select_staff_ids, function (a) { return a != -1; })
                $.ajax({
                    type: 'POST',
                    url : merchant_common_ops.buildMerchantUrl('/style/index/distribution'),
                    data: {
                        group_id: data.group_id,
                        staff_ids: select_staff_ids
                    },
                    dataType: 'json',
                    success:function (res) {
                        $.close(index);
                        //
                        // var callback = res.code != 200 ? null : function () {
                        //     location.href = merchant_common_ops.buildMerchantUrl('/style/index/index');
                        // };

                        return $.msg(res.msg, res.code == 200 , callback);
                    },
                    error: function () {
                        $.close(index);
                    }
                });
                return false;
            });
        });
        $('.checkAll').change(function () {
            let flag = $(this)[0].checked;
            $(this).parent('.layui-input-inline').find('input').each(function () {
                $(this).prop('checked',flag);
            });
        });
        $('.check').change(function () {
            var checkFlag = 1;
            $(this).parent('.layui-input-inline').children('input[class="check"]').each(function () {
                !($(this).is(':checked')) ? checkFlag = 0 : '';
            });
            $(this).parent('.layui-input-inline').find('input[class="checkAll"]').prop('checked',Boolean(checkFlag));
        });
        $('.layui-input-inline').each(function () {
            let checkFlag = 1;
            $(this).find('input[class="check"]').each(function () {
                !($(this).is(':checked')) ? checkFlag = 0 : '';
            });
            $(this).find('input[class="checkAll"]').prop('checked',Boolean(checkFlag));
        });
    },
};


$(document).ready(function () {
    merchant_style_index_assign_ops.init();
});
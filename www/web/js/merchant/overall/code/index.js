;

var merchant_overall_code_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind:function () {
        layui.use('form', function () {
            var form = layui.form;

            form.on('select(choice)', function (event) {
                var value = event.value;
                if(!(/^\d+$/.test(value))) {
                    return $.msg('请选择正确的风格');
                }

                var index = $.loading(1,{shade: .5});

                $.ajax({
                    type: 'POST',
                    url: merchant_common_ops.buildMerchantUrl('/overall/code/obtain'),
                    data: {
                        group_id: value
                    },
                    dataType: 'json',
                    success:function (response) {
                        $.close(index);
                        if(response.code != 200) {
                            $('#style-script pre').text('请选择风格');
                            return $.msg(response.msg);
                        }

                        $('#style-script pre').text(response.data);
                        return $.msg(response.msg);
                    },
                    error: function () {
                        $.close(index);
                    }
                })
            });
        });
    }
};

$(document).ready(function () {
    merchant_overall_code_index_ops.init();
});
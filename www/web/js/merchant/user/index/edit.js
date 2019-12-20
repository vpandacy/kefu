;
var merchant_style_index_edit_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var that = this;
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(userFrom)', function (data) {
                data = data.field;
                // 保存数据
                var index = $.loading(1,{shade: .5});

                $.ajax({
                    type: 'POST',
                    url : merchant_common_ops.buildMerchantUrl('/user/index/edit'),
                    data: data,
                    dataType: 'json',
                    success:function (res) {
                        $.close(index);

                        var callback = res.code != 200 ? null : function () {
                            location.href = merchant_common_ops.buildMerchantUrl('/user/index/index');
                        };

                        return $.msg(res.msg, res.code == 200 , callback);
                    },
                    error: function () {
                        $.close(index);
                    }
                });
                return false;
            });

            form.on('select(province)',function (row) {
                var province_id = row.value;

                if(!province_id) {
                    return 0;
                }

                // 然后找到对应的接口获取.
                var index = $.loading(1,{shade: .5});
                $.ajax({
                    type: 'POST',
                    url: merchant_common_ops.buildMerchantUrl('/user/index/province'),
                    data: {
                        province_id: province_id,
                    },
                    dataType: 'json',
                    success:function (res) {
                        $.close(index);
                        if(res.code != 200) {
                            return $.msg(res.msg);
                        }
                        var options = res.data.map(function (row) {
                            return '<option value="'+row.id+'">'+row.name+'</option>';
                        });

                        $('[name=city_id]').html(options.join(''));
                        form.render();
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
    merchant_style_index_edit_ops.init();
});
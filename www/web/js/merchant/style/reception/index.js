;

var merchant_style_reception_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var that = this;
        layui.use(['form'], function () {
            var form = layui.form;
            
            form.on('select(choice)', function (event) {
                var value = event.value;

                if(!(/^\d+$/.test(value))) {
                    return $.msg('请选择正确的风格');
                }

                that.getRule(form, value);
            });

            // 初始化.
            that.getRule(form, 0);
            // 监听信息.
            form.on('submit(info)', function (event) {
                var data = event.field;
                var index = $.loading(1, {shade: .5});

                $.ajax({
                    type: 'POST',
                    url: merchant_common_ops.buildMerchantUrl('/style/reception/save'),
                    data: data,
                    dataType: 'json',
                    success:function (res) {
                        $.close(index);
                        return $.msg(res.msg, res.code == 200);
                    },
                    error: function () {
                        $.close(index);
                    }
                });
                return false;
            });
        });
    },
    // 这里要进行初始化设置.
    getRule: function (form, id) {
        var index = $.loading(1, {shade: .5}),
            that = this;
        $.ajax({
            type: 'POST',
            url: merchant_common_ops.buildMerchantUrl('/style/reception/info'),
            dataType: 'json',
            data: {
                group_chat_id: id
            },
            success:function (res) {
                $.close(index);
                var data = res.data;

                // 接待策略
                that.choiceRadio('reception_strategy', data ? data.reception_strategy : 0);
                that.choiceRadio('distribution_mode', data ? data.distribution_mode : 0);
                that.choiceRadio('reception_rule', data ? data.reception_rule : 0);
                that.choiceRadio('shunt_mode', data ? data.shunt_mode : 0);
                // 重绘
                form.render();
            },
            error: function () {
                $.close(index);
            }
        })
    },
    choiceRadio: function (name, value) {
        $('[name='+ name +']').each(function (index,ele) {
            ele = $(ele);
            if(ele.val() != value) {
                ele.removeAttr('checked');
            }else{
                ele.attr('checked',true);
            };
        });
    }
};

$(document).ready(function () {
    merchant_style_reception_index_ops.init();
});
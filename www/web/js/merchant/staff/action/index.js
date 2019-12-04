;
var staff_action_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind:function () {
        layui.use(['form'],function () {
            var form = layui.form;

            form.on('radio(choice)', function () {
                var role_id = this.value,
                    index = $.loading(1,{shade: .5});

                $.ajax({
                    type: 'POST',
                    url : common_ops.buildMerchantUrl('/staff/action/list'),
                    data: {
                        role_id: role_id
                    },
                    dataType:'json',
                    success:function (response) {
                        $.close(index);
                        if(response.code != 200) {
                            return $.msg(response.msg);
                        }

                        var action_ids = response.data;

                        $('.action').each(function () {
                            console.dir(action_ids.indexOf(this.value) >= 0);
                            if(action_ids.indexOf(this.value) >= 0) {
                                $(this).attr('checked','checked');
                            }else{
                                $(this).removeAttr('checked');
                            }
                        });

                        form.render('checkbox');
                    },
                    error: function () {
                        $.close(index);
                    }
                })
                // 开始增加判断了.
            });

            form.on('submit(*)', function (data) {
                data = data.field;

                var permission_ids = [];

                $('input[type=checkbox]:checked').each(function() {
                    permission_ids.push($(this).val());
                });

                if(!data.hasOwnProperty('role_id')) {
                    return $.msg('请选择角色来保存');
                }

                if(permission_ids.length <= 0) {
                    return $.msg('请选择对应的权限');
                }


                var index = $.loading(1, {shade: 0.5});
                $.ajax({
                    type: 'POST',
                    data: {
                        role_id: data.role_id,
                        permissions: permission_ids
                    },
                    dataType: 'json',
                    url: common_ops.buildMerchantUrl('/staff/action/save'),
                    success:function (response) {
                        $.close(index);
                        if(response.code != 200) {
                            return $.msg(response.msg);
                        }

                        index = $.alert(response.msg, function () {
                            $.close(index);
                        });
                    },
                    error:function () {
                        $.close(index);
                    }
                })
            });

        });
    }
};

$(document).ready(function () {
    staff_action_index_ops.init();
});
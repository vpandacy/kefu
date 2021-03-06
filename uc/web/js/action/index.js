;
var staff_action_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind:function () {
        layui.use(['form'],function () {
            var form = layui.form;

            form.on('select(choice)', function (data) {
                var role_id = data.value;

                $('.action').each(function () {
                    $(this).prop('checked', false);
                    form.render('checkbox');
                });

                var index = $.loading(1,{shade: .5});

                $.ajax({
                    type: 'POST',
                    url : uc_common_ops.buildUcUrl('/action/list'),
                    data: {
                        role_id: role_id
                    },
                    dataType:'json',
                    success:function (res) {
                        $.close(index);
                        if(res.code != 200) {
                            return $.msg(res.msg);
                        }

                        var action_ids = res.data;

                        $('.action').each(function () {
                            if(action_ids.indexOf(this.value) >= 0) {
                                $(this).prop('checked', true);
                            }else{
                                $(this).prop('checked', false);
                            }

                            form.render('checkbox');
                        });
                    },
                    error: function () {
                        $.close(index);
                    }
                })
            });

            form.on('submit(*)', function (data) {
                data = data.field;

                var permission_ids = [];

                $('input[type=checkbox]:checked').each(function() {
                    permission_ids.push($(this).val());
                });

                if(!data.hasOwnProperty('role_id') || data.role_id <= 0) {
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
                    url: uc_common_ops.buildUcUrl('/action/save'),
                    success:function (res) {
                        $.close(index);
                        return $.msg(res.msg, res.code == 200);
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
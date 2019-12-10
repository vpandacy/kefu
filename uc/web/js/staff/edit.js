;
var uc_staff_edit_ops = {
    init: function () {
        this.eventBind();

        uploader.init('upload_container', 'upload', this, 'hsh');
    },
    eventBind: function () {
        var that = this;
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(staffFrom)', function (data) {


                data = data.field;

                var role_ids = [];
                $('[name=role_ids]:checked').each(function () {
                    role_ids.push(this.value);
                });
                // 添加权限.
                data.role_ids = role_ids;

                if(data.id > 0 && data.password) {
                    var index = $.confirm('您确认要修改密码吗,修改过后会导致该用户退出登录',function () {
                        $.close(index);
                        // 保存数据.
                        that.save(data);
                    });
                    return false;
                }
                // 保存数据
                that.save(data);
                return false;
            });
        });
    },
    save:function (data) {
        var index = $.loading(1,{shade: .5});
        $.ajax({
            type: 'post',
            url: uc_common_ops.buildUcUrl('/staff/save'),
            dataType: 'json',
            data: data,
            success:function (response) {
                $.close(index);
                if(response.code != 200) {
                    return $.msg(response.msg);
                }

                return $.alert(response.msg, function () {
                    history.go(-1);
                });
            },
            error: function () {
                $.close(index);
            }
        })
    },
    // 七牛上传成功所调用的函数.
    uploadSuccess:function (file_key, wrapper) {
        var img_wrapper = $('#' + wrapper).parents('.layui-form-item').find('.img-wrapper');
        $('#' + wrapper + ' [name=avatar]').val(file_key);
        img_wrapper.html([
            '<div class="layui-input-block">',
                '<img width="100" height="100" src="', uc_common_ops.buildPicStaticUrl('hsh', file_key) ,'" alt="">',
            '</div>'
        ].join(''));
    },
    // 七牛上传失败所调用的函数
    uploadError: function (up, err, errTip) {
        $.msg(errTip);
    }
};


$(document).ready(function () {
    uc_staff_edit_ops.init();
});
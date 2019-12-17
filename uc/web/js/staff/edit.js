;
var uc_staff_edit_ops = {
    init: function () {
        this.eventBind();
        this.layuiSelect();
        uploader.init('upload_container', 'upload', this, 'hsh');
    },
    eventBind: function () {
        var that = this;
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(staffFrom)', function (data) {


                data = data.field;

                var role_ids = [];
                $('[name=role_ids]:selected').each(function () {
                    role_ids.push(this.value);
                });
                console.log(role_ids)
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
    layuiSelect: function () {
        layui.use(['multiSelect'],function() {
            var $ = layui.jquery,form = layui.form,multiSelect = layui.multiSelect;
            $('#get-val').click(function() {
                var vals = [],
                    texts = [];
                $('select[multiple] option:selected').each(function() {
                    vals.push($(this).val());
                    texts.push($(this).text());
                })
                console.dir(vals);
                console.dir(texts);
            })
            form.on('select(test)',function(data){
                console.dir(data);
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
            success:function (res) {
                $.close(index);
                return $.msg(res.msg, res.code == 200 );
            },
            error: function () {
                $.close(index);
            }
        })
    },
    // 七牛上传成功所调用的函数.
    uploadSuccess:function (file_key, wrapper) {
        var img_wrapper = $('#' + wrapper).find('img')
        img_wrapper.attr('src',uc_common_ops.buildPicStaticUrl('hsh', file_key))
        console.log(img_wrapper)
        $('#' + wrapper + ' [name=avatar]').val(file_key);
    },
    // 七牛上传失败所调用的函数
    uploadError: function (up, err, errTip) {
        $.msg(errTip);
    }
};


$(document).ready(function () {
    uc_staff_edit_ops.init();
});
;


var overall_company_ops = {
    init: function () {
        this.eventBind();
        uploader.init('upload_container', 'logo', this, 'hsh');
    },
    eventBind: function () {
        layui.use(['form'], function () {
            var form = layui.form;

            // 这里可以弄两个接口吧.
            form.on('submit(info)',function (data) {
                if(!data.field.logo) {
                    return $.msg('请上传企业logo图片');
                }
                var index = $.loading(1, {shade: .5});
                $.ajax({
                    type: 'POST',
                    url: uc_common_ops.buildUcUrl('/company/save'),
                    data: data.field,
                    dataType:'json',
                    success:function (res) {
                        $.close(index);
                        // 暂时不做任何处理.
                        return $.msg(res.msg, res.code == 200);
                    },
                    error: function () {
                        $.close(index);
                    }
                });

                return false;
            });

            // 这里弄两个接口.
            form.on('submit(settings)', function (data) {
                var index = $.loading(1, {shade: .5});
                $.ajax({
                    type: 'POST',
                    url: uc_common_ops.buildUcUrl('/company/setting'),
                    data: data.field,
                    dataType:'json',
                    success:function (res) {
                        $.close(index);
                        // 暂时不做任何处理.
                        return $.msg(res.msg, res.code == 200);
                    },
                    error: function () {
                        $.close(index);
                    }
                });

                return false;
            });
        })
    },
    uploadSuccess: function (file_key, wrapper) {
        var img_wrapper = $('#' + wrapper).parents('.layui-form-item').find('.img-wrapper');
        $('#' + wrapper + ' [name=logo]').val(file_key);
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
    overall_company_ops.init();
});
;

var merchant_style_setting_index_ops = {
    init: function () {
        this.eventBind();

        uploader.init('upload_container', 'logo', this, 'hsh');
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

                that.getSettings(form, value);
            });

            // 初始化.
            that.getSettings(form, 0);
            // 监听信息.
            form.on('submit(info)', function (event) {
                var data = event.field;
                var index = $.loading(1, {shade: .5});

                $.ajax({
                    type: 'POST',
                    url: merchant_common_ops.buildMerchantUrl('/style/setting/save'),
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
    getSettings: function (form, id) {
        var index = $.loading(1, {shade: .5}),
            that = this;

        $.ajax({
            type: 'POST',
            url: merchant_common_ops.buildMerchantUrl('/style/setting/info'),
            dataType: 'json',
            data: {
                group_chat_id: id
            },
            success:function (res) {
                $.close(index);
                var data = res.data;

                // 接待策略
                that.choiceRadio('is_active', data ? data.is_active : 0);
                that.choiceRadio('is_force', data ? data.is_force : 0);
                that.choiceRadio('is_history', data ? data.is_history : 0);
                that.choiceRadio('is_show_num', data ? data.is_show_num : 0);
                that.choiceRadio('windows_status', data ? data.windows_status : 0);
                $('[name=company_name]').val(data ? data.company_name : '');
                $('[name=company_desc]').val(data ? data.company_desc : '');
                $('[name=lazy_time]').val(data ? data.lazy_time : '');
                $('[name=province_id]').val(data ? data.province_id : 0);

                if(data && data.company_logo) {
                    $('#upload_container [name=company_logo]').val(data.company_logo);
                    $('.img-wrapper').html([
                        '<div class="layui-input-block">',
                            '<img width="100" height="100" src="', merchant_common_ops.buildPicStaticUrl('hsh', data.company_logo) ,'" alt="">',
                        '</div>'
                    ].join(''));
                }

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
                ele.prop('checked', false);
            }else{
                ele.prop('checked',true);
            }
        });
    },
    // 七牛上传成功所调用的函数.
    uploadSuccess:function (file_key, wrapper) {
        var img_wrapper = $('#' + wrapper).parents('.layui-form-item').find('.img-wrapper');
        $('#' + wrapper + ' [name=company_logo]').val(file_key);
        img_wrapper.html([
            '<div class="layui-input-block">',
                '<img width="100" height="100" src="', merchant_common_ops.buildPicStaticUrl('hsh', file_key) ,'" alt="">',
            '</div>'
        ].join(''));
    },
    // 七牛上传失败所调用的函数
    uploadError: function (up, err, errTip) {
        $.msg(errTip);
    }
};

$(document).ready(function () {
    merchant_style_setting_index_ops.init();
});
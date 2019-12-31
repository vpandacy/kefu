;

var merchant_style_setting_index_ops = {
    data:[],
    table: null,
    init: function () {
        this.eventBind();

        uploader.init('upload_container', 'logo', this, 'hsh');
    },
    eventBind: function () {
        var that = this;
        layui.use(['form','table'], function () {
            var form = layui.form;

            that.table = layui.table;

            form.on('select(choice)', function (event) {
                var value = event.value;

                if(!(/^\d+$/.test(value))) {
                    return $.msg('请选择正确的风格');
                }

                that.getSettings(form, value);
            });

            that.table.render({
                elem: '#repeatTable',
                page: false,
                data: that.data,
                defaultToolbar: [],
                cols: [[
                    {field: 'time',title: '发起时间(秒)'},
                    {field: 'content', title: '发起语'},
                    {title: '操作', toolbar: '#toolbar'}
                ]],
                toolbar: '#tool',
                id: 'repeatTable'
            });
            // 添加
            that.table.on('toolbar(repeatTable)', function (event) {
                if(event.event != 'add') {
                    return false;
                }

                $.open({
                    title: '添加发起语',
                    content: $('.publish-form').html(),
                    btn: ['添加','取消'],
                    yes: function (index) {
                        var data = {
                            time: parseInt($('.layui-layer-dialog [name=time]').val()),
                            content: $('.layui-layer-dialog [name=content]').val()
                        };

                        if(!(/^\d+$/.test(data.time))) {
                            return $.msg('请填写正确的发起时间');
                        }

                        if(data.content.length <= 0 || data.content.length > 255) {
                            return $.msg('请输入正确的发起内容');
                        }

                        that.data.push(data);
                        that.reloadRepeatTable()
                        $.close(index);
                    },
                    btn2: function (index) {
                        $.close(index);
                    },
                    area:['600px']
                });
            });

            // 删除
            that.table.on('tool(repeatTable)', function (event) {
                if(event.event != 'delete') {
                    return false;
                }

                var index = event.tr[0].getAttribute('data-index')

                that.data = that.data.filter(function (row,cur) {
                    return cur != index;
                });
                // 删除本身.
                event.del();
            });

            // 初始化.
            that.getSettings(form, 0);

            // 监听信息.
            form.on('submit(info)', function (event) {
                var data = event.field;
                var index = $.loading(1, {shade: .5});

                // 添加数据保存.
                data.repeat_setting = JSON.stringify(that.data);

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
                that.choiceRadio('is_repeat', data ? data.is_repeat : 0);

                $('[name=company_name]').val(data ? data.company_name : '');
                $('[name=company_desc]').val(data ? data.company_desc : '');
                $('[name=times]').val(data ? data.times : '');

                if(data && data.company_logo) {
                    $('#upload_container [name=company_logo]').val(data.company_logo);
                    $('.img-wrapper').html([
                        '<div class="layui-input-block">',
                            '<img width="100" height="100" src="', merchant_common_ops.buildPicStaticUrl('hsh', data.company_logo) ,'" alt="">',
                        '</div>'
                    ].join(''));
                }else{
                    $('.img-wrapper').html('')
                }

                if(res.data.repeat_setting) {
                    that.data = JSON.parse(res.data.repeat_setting);
                    // 重新渲染.
                    that.reloadRepeatTable();
                }

                // 重绘
                form.render();
            },
            error: function () {
                $.close(index);
            }
        })
    },
    // 选择按钮.
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
    },
    reloadRepeatTable: function () {
        this.table.reload('repeatTable',{
            data: this.data
        });
    }
};

$(document).ready(function () {
    merchant_style_setting_index_ops.init();
});
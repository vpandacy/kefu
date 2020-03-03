;

var merchant_style_setting_index_ops = {
    data: [],
    table: null,
    umconfig: {
        initialFrameWidth: 640,
        initialFrameHeight: 275,
        toolbar: [
            'undo', //撤销
            'bold', //加粗
            'italic', //斜体
            'underline', //下划线
            'strikethrough', //删除线
            'subscript', //下标
            'fontborder', //字符边框
            'superscript', //上标
            'pasteplain', //纯文本粘贴模式
            'selectall', //全选
            'time', //时间
            'date', //日期
            'cleardoc', //清空文档
            'fontsize', //字号
            'paragraph', //段落格式
            'emotion', //表情
            'spechars', //特殊字符
            'searchreplace', //查询替换
            'forecolor', //字体颜色
            'backcolor', //背景色
            'lineheight', //行间距
            'touppercase', //字母大写
            'tolowercase' //字母小写
        ]
    },
    init: function () {
        this.eventBind();

        uploader.init('upload_container', 'logo', this, 'hsh');
    },
    eventBind: function () {
        var that = this;
        layui.use(['form', 'table'], function () {
            var form = layui.form;

            that.table = layui.table;

            form.on('select(choice)', function (event) {
                var value = event.value;

                if (!(/^\d+$/.test(value))) {
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
                    {field: 'time', title: '发起时间(秒)'},
                    {field: 'content', title: '发起语'},
                    {title: '操作', toolbar: '#toolbar'}
                ]],
                toolbar: '#tool',
                id: 'repeatTable'
            });
            // 添加
            that.table.on('toolbar(repeatTable)', function (event) {
                if (event.event != 'add') {
                    return false;
                }
                var um = null;
                $.open({
                    title: '添加发起语',
                    content: $('.publish-form').html(),
                    btn: ['添加', '取消'],
                    yes: function (index) {
                        var data = {
                            time: parseInt($('.layui-layer-dialog [name=time]').val()),
                            content: um.getContent()
                        };

                        if (!(/^\d+$/.test(data.time))) {
                            return $.msg('请填写正确的发起时间');
                        }

                        if (data.content.length <= 0 || data.content.length > 255) {
                            return $.msg('请输入符合要求的发起内容,字符长度不能大于250');
                        }

                        that.data.push(data);
                        that.reloadRepeatTable();
                        um.destroy();
                        $.close(index);
                    },
                    btn2: function (index) {
                        um.destroy();
                        $.close(index);
                    },
                    cancel: function () {
                        um.destroy();
                    },
                    area: ['800px', '550px']
                });

                $('.layui-layer-dialog .layui-textarea').attr('id', 'editor');
                um = UM.getEditor('editor', that.umconfig);
            });

            // 删除
            that.table.on('tool(repeatTable)', function (event) {
                if (event.event == 'delete') {
                    var index = event.tr[0].getAttribute('data-index')

                    that.data = that.data.filter(function (row, cur) {
                        return cur != index;
                    });
                    // 删除本身.
                    event.del();
                    return false;
                }
                var curr = event.tr[0].getAttribute('data-index'),
                    data = that.data[curr],
                    um = null;

                $.open({
                    title: '添加发起语',
                    content: $('.publish-form').html(),
                    btn: ['添加', '取消'],
                    yes: function (index) {
                        var data = {
                            time: parseInt($('.layui-layer-dialog [name=time]').val()),
                            content: um.getContent()
                        };

                        if (!(/^\d+$/.test(data.time))) {
                            return $.msg('请填写正确的发起时间');
                        }

                        if (data.content.length <= 0 || data.content.length > 255) {
                            return $.msg('请输入正确的发起内容');
                        }

                        that.data[curr] = data;
                        that.reloadRepeatTable();
                        um.destroy();
                        $.close(index);
                    },
                    btn2: function (index) {
                        $.close(index);
                        um.destroy();
                    },
                    cancel: function () {
                        um.destroy();
                    },
                    area: ['800px', '550px']
                });

                $('.layui-layer-dialog [name=time]').val(data.time);
                $('.layui-layer-dialog [name=content]').val(data.content);

                $('.layui-layer-dialog .layui-textarea').attr('id', 'editor');
                um = UM.getEditor('editor', that.umconfig);
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
                    success: function (res) {
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
            success: function (res) {
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

                if (data && data.company_logo) {
                    $('#upload_container [name=company_logo]').val(data.company_logo);
                    $('.img-wrapper').html([
                        '<div class="layui-input-block">',
                        '<img width="100" height="100" src="', merchant_common_ops.buildPicStaticUrl('hsh', data.company_logo), '" alt="">',
                        '</div>'
                    ].join(''));
                } else {
                    $('.img-wrapper').html('')
                }

                if (res.data.repeat_setting) {
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
        $('[name=' + name + ']').each(function (index, ele) {
            ele = $(ele);
            if (ele.val() != value) {
                ele.prop('checked', false);
            } else {
                ele.prop('checked', true);
            }
        });
    },
    // 七牛上传成功所调用的函数.
    uploadSuccess: function (file_key, wrapper) {
        var img_wrapper = $('#' + wrapper).parents('.layui-form-item').find('.img-wrapper');
        $('#' + wrapper + ' [name=company_logo]').val(file_key);
        img_wrapper.html([
            '<div class="layui-input-block">',
            '<img width="100" height="100" src="', merchant_common_ops.buildPicStaticUrl('hsh', file_key), '" alt="">',
            '</div>'
        ].join(''));
    },
    // 七牛上传失败所调用的函数
    uploadError: function (up, err, errTip) {
        $.msg(errTip);
    },
    reloadRepeatTable: function () {
        this.table.reload('repeatTable', {
            data: this.data
        });
    }
};

$(document).ready(function () {
    merchant_style_setting_index_ops.init();
});
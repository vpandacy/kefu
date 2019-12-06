;var staff_role_index_osp = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var that = this;
        layui.use('table', function(){
            var table = layui.table;

            table.render({
                elem: '#roleTable'
                ,url:url_manager.buildUcUrl('/role/list')
                ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
                ,defaultToolbar: []
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {type:'checkbox', fixed: 'left'}
                    ,{field:'id', title: '序号'}
                    ,{field:'name', title: '角色名'}
                    ,{field:'status', title: '状态', templet: function (row) {
                        return row.status == 1 ? '正常' : '已禁用';
                    }}
                    ,{field:'created_time', title: '创建时间'}
                    ,{title:'操作', toolbar: '#barDemo', width:150, fixed: 'right'}
                ]]
                ,id: 'roleTable'
                ,page: true
            });

            table.on('toolbar(roleTable)',function (action) {
                if(action.event == 'add') {
                    // 添加和编辑.
                    return that.edit(table);
                }

                var select_row = table.checkStatus('roleTable');
                if(!select_row.data.length) {
                    return $.msg('请选中需要恢复的角色');
                }

                var ids = select_row.data.map(function (staff) {
                    return staff.id;
                });

                $.confirm('您确认要将恢复这些角色吗???', function (index) {
                    $.close(index);
                    index = $.loading(1,{shade: .5});

                    $.ajax({
                        type: 'POST',
                        url: url_manager.buildUcUrl('/role/recover'),
                        data: {
                            ids: ids
                        },
                        dataType: 'json',
                        success:function (response) {
                            $.close(index);
                            if(response.code != 200) {
                                return $.msg(response.msg);
                            }

                            index = $.alert(response.msg,function () {
                                $.close(index);
                                table.reload('roleTable');
                            });
                        },
                        error: function () {
                            $.close(index);
                        }
                    })
                })
                return false;
            });

            table.on('tool(roleTable)',function (row) {
                if(row.event == 'edit') {
                    return that.edit(table, row.data);
                }

                $.confirm('您确认要禁用该角色吗?', function (index) {
                    $.close(index);
                    index = $.loading(1,{shade: .5});
                    $.ajax({
                        type: 'POST',
                        url: url_manager.buildUcUrl('/role/disable'),
                        dataType: 'json',
                        data: {
                            id: row.data.id
                        },
                        success:function (response) {
                            $.close(index);
                            if(response.code != 200) {
                                return $.msg(response.msg);
                            }

                            index = $.alert(response.msg,function () {
                                $.close(index);
                                table.reload('roleTable');
                            });
                        },
                        error: function () {
                            $.close(index);
                        }
                    })
                });
                return false;
            });

        });
    },
    edit: function (table, row) {
        $.open({
            title: row ? '编辑角色信息' : '添加角色信息',
            content: [
                '<div class="layui-form-item">',
                '    <label class="layui-form-label">角色名称</label>',
                '    <div class="layui-input-block">',
                '        <input type="text" name="name" value="',row ? row.name : '','" placeholder="请输入角色名称" autocomplete="off" class="layui-input">',
                '    </div>',
                '</div>',
                '<input type="hidden" name="id" value="',row ? row.id : '','">'
            ].join(''),
            btn: [
                '立即提交','重置'
            ],
            area:['600px'],
            yes:function (index) {
                var name = $('[name=name]').val(),
                    id = $('[name=id]').val();

                if(!name || name.length > 255) {
                    return $.msg('请填写正确的角色名称');
                }

                if(id && !(/^\d+$/.test(id))) {
                    return $.msg('非法请求');
                }

                var lay_index = $.loading(1, {shade: .5});
                $.ajax({
                    type: 'POST',
                    url: url_manager.buildUcUrl('/role/save'),
                    data: {
                        name: name,
                        id: id
                    },
                    dataType: 'json',
                    success:function (response) {
                        $.close(lay_index);
                        if(response.code != 200) {
                            return $.msg(response.msg);
                        }

                        lay_index =  $.alert(response.msg,function () {
                            table.reload('roleTable');

                            $.close(lay_index);
                            $.close(index);
                        });
                    },
                    error:function () {
                        $.close(lay_index);
                    }
                });
            },
            btn2: function () {
                $('[name=name]').val('');
                return false;
            }
        });
    }
};


$(document).ready(function () {
    staff_role_index_osp.init();
})
;
var uc_staff_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        layui.use('table', function(){
            var table = layui.table;
            // 表格渲染.
            table.render(uc_common_ops.buildLayuiTableConfig({
                elem: '#staff'
                ,url: uc_common_ops.buildUcUrl('/staff/index')
                ,where: {
                    mobile: $('.search-wrapper [name=mobile]').val(),
                    email: $('.search-wrapper [name=email]').val(),
                    department_id: $('.search-wrapper [name=department_id]').val()
                }
                ,toolbar: '#staffBar' //开启头部工具栏，并为其绑定左侧模板
                ,defaultToolbar: []
                ,height: 600
                ,cellMinWidth: 50 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    { type:'checkbox', fixed: 'left'}
                    ,{field:'id',  width: 60, title: '序号'}
                    ,{field:'email',title: '邮箱'}
                    ,{field:'name', title: '姓名', templet: function (row) {
                        return row.name ? row.name : '暂无';
                    }}
                    ,{field:'nickname', title: '昵称', templet: function (row) {
                        return row.nickname ? row.nickname : '暂无';
                    }}
                    ,{field:'mobile', title: '手机号', width: 130, templet: function (row) {
                        return row.mobile ? row.mobile : '暂无';
                    }} //minWidth：局部定义当前单元格的最小宽度，layui 2.2.1 新增
                    ,{field:'department', title: '所属部门',width:100}
                    ,{field:'listen_nums', width: 100, title: '接听数'}
                    ,{field:'status', width: 100, title: '状态', templet: function (row) {
                        var map = {
                            '0'  : '禁止登录',
                            '1'  : '正常'
                        };

                        return map[row.status];
                    }}
                    ,{field:'created_time', width: 170, title: '创建时间'}
                    ,{field: 'right', title:'操作',width: 130, toolbar: '#barDemo', fixed: 'right'}
                ]]
                ,id: 'staff'
                ,limit: 15
            }));

            // 表头事件.
            table.on('toolbar(staff)',function (row) {
                if(row.event == 'add') {
                    location.href = uc_common_ops.buildUcUrl('/staff/edit');
                    return false;
                }
                var select_row = table.checkStatus('staff');
                if(!select_row.data.length) {
                    return $.msg('请选中需要恢复的帐号');
                }

                var ids = select_row.data.map(function (staff) {
                    return staff.id;
                });

                $.confirm('您确认要将这些帐号恢复登录吗???', function (index) {
                    $.close(index);
                    index = $.loading(1,{shade: .5});

                    $.ajax({
                        type: 'POST',
                        url: uc_common_ops.buildUcUrl('/staff/recover'),
                        data: {
                            ids: ids,
                        },
                        dataType: 'json',
                        success:function (res) {
                            $.close(index);

                            var callback = res.code != 200 ? null : function () {
                                table.reload('staff');
                            };

                            return $.msg(res.msg, res.code == 200 , callback);
                        },
                        error: function () {
                            $.close(index);
                        }
                    })
                })
            });

            // 行内事件.
            table.on('tool(staff)', function (row) {
                if(row.event == 'edit') {
                    location.href = uc_common_ops.buildUcUrl('/staff/edit',{
                        staff_id: row.data.id,
                    });
                    return false;
                }

                $.confirm('您确认要禁用该帐号吗?', function (index) {
                    $.close(index);
                    index = $.loading(1,{shade: .5});
                    $.ajax({
                        type: 'POST',
                        url: uc_common_ops.buildUcUrl('/staff/disable'),
                        dataType: 'json',
                        data: {
                            id: row.data.id
                        },
                        success:function (res) {
                            $.close(index);

                            var callback = res.code != 200 ? null : function () {
                                table.reload('staff');
                            };

                            return $.msg(res.msg, res.code == 200 , callback);
                        },
                        error: function () {
                            $.close(index);
                        }
                    })
                });
            });

            $('.layui-table-box').append('<div class="filter_panel dis_none" >' +
                '<form class="layui-form layui-form-pane " action="">\n' +
                $('.search-wrapper').html(),
                '</form>' +
                '</div>');

            $(".fa-glass").delegate($('.filter_panel'),"click",function(event){
                event.stopPropagation();
                $(".filter_panel").slideToggle();
            });

            // 点击除弹出层别的地方隐藏
            $(document).click(function(e){
                var target = $(e.target);
                if(target.closest(".filter_panel").length != 0) return;
                $(".filter_panel").hide();
            });
        });
    }
};

$(document).ready(function () {
    uc_staff_index_ops.init();
});
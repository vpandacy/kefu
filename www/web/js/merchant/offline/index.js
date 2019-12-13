;
var merchant_overall_offline_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind:function () {
        layui.use('table', function(){
            var table = layui.table;

            table.render(merchant_common_ops.buildLayuiTableConfig({
                elem: '#offlineTable'
                ,url:merchant_common_ops.buildMerchantUrl('/overall/offline/index')
                ,where: {
                    keyword: $('[name=keyword]').val(),
                }
                ,defaultToolbar: ['filter','print']
                ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {field:'id', title: '序号'}
                    ,{field:'group_chat', title: '风格'}
                    ,{field:'name', title: '姓名'}
                    ,{field:'mobile', title: '手机号'}
                    ,{field:'wechat', title: '微信号'}
                    ,{field:'message', title: '留言信息'}
                    ,{field:'status', title: '状态', templet: function (row) {
                        return row.status == 1 ? '已处理' : '未处理';
                    }}
                    ,{field:'created_time', title: '留言时间'}
                    ,{title:'操作', toolbar: '#barDemo', width:150, fixed: 'right'}
                ]]
                ,id: 'offlineTable'
            }));


            table.on('tool(offlineTable)', function (event) {
                if(event.event != 'handle') {
                    return false;
                }

                if(event.data.status != 0) {
                    return $.msg('该条留言已经处理了,请勿重复处理');
                }

                $.confirm('您确定要标记该条留言为已处理吗?', function () {
                    var index = $.loading(1, {shade: .5});
                    $.ajax({
                        type: 'POST',
                        url: merchant_common_ops.buildMerchantUrl('/overall/offline/save'),
                        data: {
                            id: event.data.id
                        },
                        dataType: 'json',
                        success:function (res) {
                            $.close(index);
                            var callback = res.code != 200 ? null: function () {
                                table.reload('offlineTable');
                            };

                            return $.msg(res.msg, res.code ==200, callback);
                        },
                        error: function () {
                            $.close(index);
                        }
                    })
                    return true;
                });
            });
        });
    }
};

$(document).ready(function () {
    merchant_overall_offline_index_ops.init();
});
;
var merchant_black_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        layui.use('table', function(){
            var table = layui.table;

            table.render(merchant_common_ops.buildLayuiTableConfig({
                elem: '#blackListTable'
                ,url: merchant_common_ops.buildMerchantUrl('/black/index/index')
                ,toolbar: '#blackToolbar' //开启头部工具栏，并为其绑定左侧模板
                ,defaultToolbar: ['filter','exports']
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {field:'id', width:80, title: '序号'}
                    ,{field:'ip',  title: 'IP地址'}
                    ,{field:'uuid',  title: '访客编号'}
                    ,{field:'staff_name', title: '客服'}
                    ,{field:'expired_time', title: '失效时间'}
                    ,{field:'created_time', title: '添加时间'}
                    ,{title:'操作', toolbar: '#blackBar', width:150, fixed: 'right'}
                ]]
                ,id: 'blackListTable'
            }));

            table.on('toolbar(blackListTable)', function (event) {
                if(event.event != 'add') {
                    return true;
                }

                // 这里要添加黑名单.
                location.href = merchant_common_ops.buildMerchantUrl('/black/index/edit');
            });
            
            
            table.on('tool(blackListTable)', function (row) {
                if(row.event != 'del') {
                    return false;
                }

                $.confirm('您确认要删除该条黑名单吗?', function (index) {
                    $.close(index);
                    index = $.loading(1,{shade: .5});
                    $.ajax({
                        type: 'POST',
                        url: merchant_common_ops.buildMerchantUrl('/black/index/disable'),
                        dataType: 'json',
                        data: {
                            id: row.data.id
                        },
                        success:function (res) {
                            $.close(index);

                            var callback = res.code != 200 ? null : function () {
                                table.reload('blackListTable');
                            };

                            return $.msg(res.msg, res.code == 200 , callback);
                        },
                        error: function () {
                            $.close(index);
                        }
                    })
                });
            });
        });
    }
};

$(document).ready(function () {
    merchant_black_index_ops.init();
});
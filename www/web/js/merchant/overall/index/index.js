;
var merchant_overall_index_ops = {
    init: function () {
        layui.use('table', function(){
            var table = layui.table;

            table.render({
                elem: '#commonWordTable'
                ,url:merchant_common_ops.buildMerchantUrl('/overall/index/list')
                ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {type:'checkbox', fixed: 'left'}
                    ,{field:'id', title: '序号'}
                    ,{field:'words', title: '常用语'}
                    ,{field:'status', title: '状态', templet: function (row) {
                        return row.status == 1 ? '正常' : '禁用';
                    }}
                    ,{field:'created_time', title: '创建时间', sort: true}
                    ,{ title:'操作', toolbar: '#barDemo', fixed: 'right'}
                ]]
                ,id: 'commonWordTable'
                ,limit: 15
                ,page: {
                    layout: ['prev', 'page', 'next', 'first', 'last' ,'skip']
                }
            });

            table.on('toolbar(commonWordTable)', function (row) {
                if(row.event == 'add') {
                    location.href = merchant_common_ops.buildMerchantUrl('/overall/index/edit');
                    return false;
                }

                if(['LAYTABLE_COLS','LAYTABLE_EXPORT','LAYTABLE_PRINT'].indexOf(row.event) >= 0) {
                    return false;
                }

                if(row.event == 'import') {
                    location.href = merchant_common_ops.buildMerchantUrl('/overall/index/import');
                    return false;
                }

                var select_row = table.checkStatus('commonWordTable');
                if(!select_row.data.length) {
                    return $.msg('请选中需要恢复的常用语');
                }

                var ids = select_row.data.map(function (words) {
                    return words.id;
                });

                $.confirm('您确认要将这些常用语恢复可用状态吗???', function (index) {
                    $.close(index);
                    index = $.loading(1,{shade: .5});

                    $.ajax({
                        type: 'POST',
                        url: merchant_common_ops.buildMerchantUrl('/overall/index/recover'),
                        data: {
                            ids: ids
                        },
                        dataType: 'json',
                        success:function (res) {
                            $.close(index);

                            var callback = res.code != 200 ? null : function () {
                                table.reload('commonWordTable');
                            };

                            return $.msg(res.msg, res.code == 200 , callback);
                        },
                        error: function () {
                            $.close(index);
                        }
                    })
                })

                return false;
            });

            table.on('tool(commonWordTable)', function (row) {
                if(row.event == 'edit') {
                    location.href = merchant_common_ops.buildMerchantUrl('/overall/index/edit',{
                        word_id: row.data.id
                    });
                    return false;
                }

                $.confirm('您确认要禁用该常用语吗?', function (index) {
                    $.close(index);
                    index = $.loading(1,{shade: .5});
                    $.ajax({
                        type: 'POST',
                        url: common_ops.buildMerchantUrl('/overall/index/disable'),
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
                                table.reload('commonWordTable');
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
    }
};

$(document).ready(function () {
    merchant_overall_index_ops.init();
});

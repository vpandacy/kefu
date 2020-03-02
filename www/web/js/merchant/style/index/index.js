;
var merchant_style_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var that = this;
        layui.use('table', function(){
            var table = layui.table;

            table.render(merchant_common_ops.buildLayuiTableConfig({
                elem: '#groupChat'
                ,url: merchant_common_ops.buildMerchantUrl('/style/index/index')
                ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
                ,defaultToolbar: []
                ,cellMinWidth: 50 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {type:'checkbox', fixed: 'left'},
                    {field:'id', width:60, title: '序号'}
                    ,{field:'title', title: '标题'}
                    ,{field:'desc' , title: '描述'}
                    ,{field:'status', width:80, title: '状态', templet: function (row) {
                        return row.status == 1 ? '正常' : '禁用';
                    }}
                    ,{field:'created_time', title: '创建时间'}
                    ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:300}
                ]]
                ,id: 'groupChat'
            }));

            table.on('toolbar(groupChat)', function (event) {
                if(event.event == 'add') {
                    location.href = merchant_common_ops.buildMerchantUrl('/style/index/edit');
                    return false;
                }

                var select_row = table.checkStatus('groupChat');
                if(!select_row.data.length) {
                    return $.msg('请选中需要恢复的风格');
                }

                var ids = select_row.data.map(function (group) {
                    return group.id;
                });

                $.confirm('您确认要将这些风格恢复使用吗???', function (index) {
                    $.close(index);
                    index = $.loading(1,{shade: .5});

                    $.ajax({
                        type: 'POST',
                        url: merchant_common_ops.buildMerchantUrl('/style/index/recover'),
                        data: {
                            ids: ids
                        },
                        dataType: 'json',
                        success:function (res) {
                            $.close(index);

                            var callback = res.code != 200 ? null : function () {
                                table.reload('groupChat');
                            };

                            return $.msg(res.msg, res.code == 200 , callback);
                        },
                        error: function () {
                            $.close(index);
                        }
                    });
                });
            });

            table.on('tool(groupChat)', function (event) {
                if(event.event == 'edit') {
                    location.href = merchant_common_ops.buildMerchantUrl('/style/index/edit', {
                        group_id: event.data.id,
                    });
                    return false;
                }

                if(event.event == 'assign') {
                    location.href = merchant_common_ops.buildMerchantUrl('/style/index/assign', {
                        group_id: event.data.id
                    });

                    return false;
                }

                if(event.event == 'get') {
                    var index = $.loading(1,{shade: .5});
                    $.ajax({
                        type: 'POST',
                        url: merchant_common_ops.buildMerchantUrl('/overall/code/obtain'),
                        dataType : 'json',
                        data: {
                            group_id: event.data.id,
                        },
                        success:function (res) {
                            $.close(index);
                            if(res.code != 200) {
                                return $.msg(res.msg);
                            }

                            var html = that.html2Escape(res.data);

                            return $.open({
                                'title': '客服代码',
                                'content': '<pre style="padding: 20px; background-color: #eeeeee;">' + html + '</pre>',
                                'area': ['1000px']
                            })
                        }
                    });
                    return false;
                }

                $.confirm('您确认要禁用该风格吗?', function (index) {
                    $.close(index);
                    index = $.loading(1,{shade: .5});
                    $.ajax({
                        type: 'POST',
                        url: merchant_common_ops.buildMerchantUrl('/style/index/disable'),
                        dataType: 'json',
                        data: {
                            id: event.data.id
                        },
                        success:function (response) {
                            $.close(index);
                            if(response.code != 200) {
                                return $.msg(response.msg);
                            }

                            index = $.alert(response.msg,function () {
                                $.close(index);
                                table.reload('groupChat');
                            });
                        },
                        error: function () {
                            $.close(index);
                        }
                    });
                });
            });
        });
    },
    html2Escape:function (sHtml) {
        return sHtml.replace(/[<>&"]/g, function(c){
            return {'<':'&lt;','>':'&gt;','&':'&amp;','"':'&quot;'}[c];
        });
    }

};

$(document).ready(function () {
    merchant_style_index_ops.init();
});
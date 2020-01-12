;
var merchant_user_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        layui.use('table', function(){
            var table = layui.table;

            table.render(merchant_common_ops.buildLayuiTableConfig({
                elem: '#userTable'
                ,url: merchant_common_ops.buildMerchantUrl('/user/index/index')
                ,defaultToolbar: ['filter','exports']
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {field:'id', width:80, title: '序号'}
                    ,{field:'staff_name', width:100, title: '操作员工'}
                    ,{field:'name', width:100, title: '姓名'}
                    ,{field:'mobile',  title: '手机号'}
                    ,{field:'email',  title: '邮箱'}
                    ,{field:'qq',  title: 'QQ号码'}
                    ,{field:'wechat',  title: '微信号'}
                    ,{field:'reg_ip',  title: '注册IP'}
                    ,{field: 'source', title: '来源', templet:function (row) {
                        var sources_map = {
                            0: '暂无',
                            1: 'PC',
                            2: '手机',
                            3: '微信'
                        };
                        return sources_map[row.source];
                    }}
                    ,{field:'desc', title: '备注'}
                    ,{field:'created_time', width: 170, title: '添加时间'}
                    ,{fixed: 'right', title:'操作', toolbar: '#userBar'}
                ]]
                ,id: 'userTable'
            }));

            table.on('tool(userTable)',function (event) {
                if(event.event != 'edit') {
                    return false;
                }

                location.href = merchant_common_ops.buildMerchantUrl('/user/index/edit',{
                    member_id: event.data.id,
                });
            })
        });
    }
};

$(document).ready(function () {
    merchant_user_index_ops.init();
});
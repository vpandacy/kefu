;var log_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var that = this;
        layui.use('table', function(){
            var table = layui.table;

            table.render(uc_common_ops.buildLayuiTableConfig({
                elem: '#logTable'
                ,where: {
                    staff_id: $('[name=staff_id]').val()
                }
                ,url:uc_common_ops.buildUcUrl('/log/index')
                ,defaultToolbar: []
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {type:'checkbox', fixed: 'left'}
                    ,{field:'mobile', title: '手机号'}
                    ,{field:'staff_name', title: '客服姓名'}
                    ,{field:'login_ip', title: '登录IP', templet: function (row) {
                        return row.login_ip + '[' + row.address +']';
                    }}
                    ,{field:'login_time', title: '登录时间'}
                    ,{field:'logout_time', title: '退出时间',templet: function (row) {
                        return row.logout_time ? row.logout_time : '无';
                    }}
                ]]
                ,id: 'logTable'
            }));
        });
    }
};


$(document).ready(function () {
    log_index_ops.init();
})
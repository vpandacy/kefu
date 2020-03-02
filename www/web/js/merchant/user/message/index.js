;
var merchant_user_message_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        layui.use('table', function(){
            var table = layui.table;

            table.render(merchant_common_ops.buildLayuiTableConfig({
                elem: '#messageTable'
                ,url: merchant_common_ops.buildMerchantUrl('/user/message/index')
                ,defaultToolbar: ['filter','exports']
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {field:'id', width:80, title: '序号'}
                    ,{field:'member_name', title: '会员名'}
                    ,{field:'staff_name', title: '客服'}
                    ,{field:'uuid',  title: '访客编号'}
                    ,{field:'content', title: '聊天内容'}
                    ,{field:'access_time', title: '来访时间'}
                    ,{field:'created_time', title: '发送时间'}
                ]]
                ,id: 'messageTable'
            }));
        });
    }
};

$(document).ready(function () {
    merchant_user_message_ops.init();
});
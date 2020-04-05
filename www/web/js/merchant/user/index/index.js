;
var merchant_user_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var table = layui.table;
        //转换静态表格
        table.init('staff_list',{ limit: 30 });
    }
};

$(document).ready(function () {
    merchant_user_index_ops.init();
});
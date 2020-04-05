;
var uc_staff_index_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var table = layui.table;
        //转换静态表格
        table.init('staff_list',{ limit: 30 });

        $(".layui-table-body .ops").click( function(){
            var msg = "你确定" + $.trim( $(this).html() ) + "账号？";
            var data = $(this).data();
            var callback = {
                "ok":function(){
                    $.ajax({
                        url: uc_common_ops.buildUcUrl('/staff/ops'),
                        type: "POST",
                        data: data,
                        dataType: 'json',
                        success: function (res) {
                            var callback = null;
                            if (res.code == 200) {
                                callback = function () {
                                    window.location.href = window.location.href;
                                }
                            }
                            $.msg(res.msg, res.code == 200,callback);
                        }
                    });
                }
            };
            $.confirm( msg ,callback);
        });
    }
};

$(document).ready(function () {
    uc_staff_index_ops.init();
});
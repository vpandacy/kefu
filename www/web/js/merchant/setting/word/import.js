;
var word_import_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        var that = this;

        //点击添加数据
        $(".step_3 .save").click(function () {
            var input_data = $(this).data("input");
            var callback = {
                "ok": function () {
                    $.ajax({
                        url: merchant_common_ops.buildMerchantUrl('/setting/word/save'),
                        data: {
                            data: input_data,
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function (res) {
                            var callback = {};
                            if (res.code == 200) {
                                callback = function () {
                                    window.location.href = merchant_common_ops.buildMerchantUrl("/overall/index/index");
                                }
                            }
                            $.alert(res.msg, callback);
                        }
                    });
                },
                "cancel": function () {

                }
            };
            $.confirm("确认要导入吗?", callback);
        });
    }
};

$(document).ready(function () {
    word_import_ops.init();
});

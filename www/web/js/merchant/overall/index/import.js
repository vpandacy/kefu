;
var merchant_overall_index_import_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        layui.use(['upload'], function () {
            var upload = layui.upload,
                index = 0;

            upload.render({
                elem: '#upload'
                ,auto: false
                ,field: 'file'
                ,size: '2048'
                ,multiple: false
                ,drag: true
                ,accept: 'file'
                ,acceptMime:'application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ,exts: 'xls|xlsx'
                ,bindAction: '#upload-button'
                ,url: merchant_common_ops.buildMerchantUrl('/overall/index/import')
                ,before: function () {
                    index = $.loading(1,{shade: .5});
                }
                ,done: function (res) {
                    $.close(index);

                    var callback = res.code != 200 ? null : function () {
                        location.href = merchant_common_ops.buildMerchantUrl('/overall/index/index')
                    };

                    return $.msg(res.msg, res.code == 200 , callback);

                },
                error: function () {
                    $.close(index);
                }
            });
        });
    }
};

$(document).ready(function () {
    merchant_overall_index_import_ops.init();
});
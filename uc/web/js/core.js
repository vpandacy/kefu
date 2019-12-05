;
! function($) {
    "use strict";
    $.extend({
        lay: {
            // alert弹层
            alert: function (content,callback,params) {
                // 确认后的回调信息.
                return layer.alert(content,params ? params : {}, callback);
            },
            // 提示框.
            msg: function (content) {
                return layer.msg(content);
            },
            // 确认框
            confirm: function (content,params,callback,cancel_callback) {
                if(typeof params == 'function') {
                    cancel_callback = callback;
                    callback = params;
                    params = {};
                }

                return layer.confirm(content,params,callback,cancel_callback);
            },
            // 加载.
            loading: function (type,params) {
                if(!type) {
                    type = 1;
                }
                return layer.load(type, params ? params : {});
            },
            // 关闭弹层
            close: function (index) {
                return layer.close(index);
            },
            // 关闭所有.
            closeAll:function () {
                return layer.closeAll();
            },
            open: function (obj) {
                return layer.open(obj);
            }
        }
    });

    //快捷调用
    $.alert     = $.lay.alert;
    $.msg       = $.lay.msg;
    $.confirm   = $.lay.confirm;
    $.loading   = $.lay.loading;
    $.open      = $.lay.open;
    $.close     = $.lay.close;
    $.closeAll  = $.lay.closeAll;
}(jQuery);
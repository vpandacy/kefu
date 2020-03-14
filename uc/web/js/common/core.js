;
// 所有基本对核心的处理都需要使用此组件.
! function($) {
    "use strict";
    $.extend({
        lay: {
            // alert弹层
            alert: function (msg, cb) {
                layer.alert(msg, {
                    yes: function (index) {
                        if (typeof cb == "function") {
                            cb();
                        }
                        layer.close(index);
                    }
                });
            },
            // 提示框.
            msg:function( msg,flag,callback ){
                callback = (callback != undefined && typeof callback == "function") ? callback : null;
                var params = {
                    "icon": 6,
                    "time": 1000,
                    "shade" :[0.5 , '#000' , true]
                };
                if( !flag ){
                    params['icon'] = 5;
                    params['time'] = 1500;
                }
                layer.msg( msg ,params,callback );
            },
            // 确认框
            confirm: function (msg,callback,btn) {

                callback = typeof callback == 'function' ? {'ok': callback, 'cancel': null} : callback;
                callback = (callback != undefined) ? callback : {'ok': null, 'cancel': null};
                btn =  (callback == undefined || btn == []) ? ['确定', '取消'] : btn;
                layer.confirm(msg, {
                    btn: btn //按钮
                }, function (index) {
                    //确定事件
                    if (typeof callback.ok == "function") {
                        callback.ok();
                    }
                    layer.close(index);
                }, function (index) {
                    //取消事件
                    if (typeof callback.cancel == "function") {
                        callback.cancel();
                    }
                    layer.close(index);
                });
            },
            tip: function (msg, target) {
                layer.tips(msg, target, {
                    tips: [3, '#e5004f']
                });
            },
            popLayer:function( url,data,params){
                var base_config = {
                    type: 1,
                    area: '500px', //宽高
                };
                var config = $.extend({},base_config,params);
                $.ajax({
                    url: url,
                    data: data,
                    type: "GET",
                    dataType: 'json',
                    success: function (res) {
                        if(res.code != 200 ){
                            $.alert(res.msg);
                            return;
                        }
                        config['content'] = res.data.content;
                        layer.open(config);
                    }
                });
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
    $.tip   = $.lay.tip;
    $.popLayer   = $.lay.popLayer;
    $.loading   = $.lay.loading;
    $.open      = $.lay.open;
    $.close     = $.lay.close;
    $.closeAll  = $.lay.closeAll;
}(jQuery);

//uc 统一JS
var user_center = {
    init: function () {
        var login_status = $(".hidden_val_wrap input[name=login_status]").val();
        if ( login_status != 1 ) {
            return;
        }
        this.center();

    },
    eventBind: function () {
        // 这里是右上角用户头像的动画效果
        var timer=null;
        $('.menu_info_link').mouseenter(function () {
            $('.menu_info_edit').show();
        }).mouseleave(function () {
            timer=setTimeout(function () {
                $('.menu_info_edit').hide();
            },2000);
        });


        $('.menu_info_edit').mouseover(function () {
            clearTimeout(timer);
            $(this).show();
        }).mouseout(function () {
            $(this).hide();
        })
    },
    center:function(){
        var that = this;
        $.ajax({
            type: 'get',
            url: common_ops_url.buildUrl('/user/center'),
            dataType: 'json',
            success: function (response) {
                if(response.code != 200 || $('.right_merchant').length <= 0) {
                    return false;
                }
                $('.right_merchant .right_top').html(response.data.html);
                // 重新注册事件.
                that.eventBind();
            }
        })
    }
};

$(document).ready( function(){
    user_center.init();
} );

var EleResize = {
    _handleResize: function (e) {
        var ele = e.target || e.srcElement;
        var trigger = ele.__resizeTrigger__;
        if (trigger) {
            var handlers = trigger.__z_resizeListeners;
            if (handlers) {
                var size = handlers.length;
                for (var i = 0; i < size; i++) {
                    var h = handlers[i];
                    var handler = h.handler;
                    var context = h.context;
                    handler.apply(context, [e]);
                }
            }
        }
    },
    _removeHandler: function (ele, handler, context) {
        var handlers = ele.__z_resizeListeners;
        if (handlers) {
            var size = handlers.length;
            for (var i = 0; i < size; i++) {
                var h = handlers[i];
                if (h.handler === handler && h.context === context) {
                    handlers.splice(i, 1);
                    return;
                }
            }
        }
    },
    _createResizeTrigger: function (ele) {
        var obj = document.createElement('object');
        obj.setAttribute('style',
            'display: block; position: absolute; top: 0; left: 0; height: 100%; width: 100%; overflow: hidden;opacity: 0; pointer-events: none; z-index: -1;');
        obj.onload = EleResize._handleObjectLoad;
        obj.type = 'text/html';
        ele.appendChild(obj);
        obj.data = 'about:blank';
        return obj;
    },
    _handleObjectLoad: function (evt) {
        this.contentDocument.defaultView.__resizeTrigger__ = this.__resizeElement__;
        this.contentDocument.defaultView.addEventListener('resize', EleResize._handleResize);
    }
};
if (document.attachEvent) {//ie9-10
    EleResize.on = function (ele, handler, context) {
        var handlers = ele.__z_resizeListeners;
        if (!handlers) {
            handlers = [];
            ele.__z_resizeListeners = handlers;
            ele.__resizeTrigger__ = ele;
            ele.attachEvent('onresize', EleResize._handleResize);
        }
        handlers.push({
            handler: handler,
            context: context
        });
    };
    EleResize.off = function (ele, handler, context) {
        var handlers = ele.__z_resizeListeners;
        if (handlers) {
            EleResize._removeHandler(ele, handler, context);
            if (handlers.length === 0) {
                ele.detachEvent('onresize', EleResize._handleResize);
                delete  ele.__z_resizeListeners;
            }
        }
    }
} else {
    EleResize.on = function (ele, handler, context) {
        var handlers = ele.__z_resizeListeners;
        if (!handlers) {
            handlers = [];
            ele.__z_resizeListeners = handlers;

            if (getComputedStyle(ele, null).position === 'static') {
                ele.style.position = 'relative';
            }
            var obj = EleResize._createResizeTrigger(ele);
            ele.__resizeTrigger__ = obj;
            obj.__resizeElement__ = ele;
        }
        handlers.push({
            handler: handler,
            context: context
        });
    };
    EleResize.off = function (ele, handler, context) {
        var handlers = ele.__z_resizeListeners;
        if (handlers) {
            EleResize._removeHandler(ele, handler, context);
            if (handlers.length === 0) {
                var trigger = ele.__resizeTrigger__;
                if (trigger) {
                    trigger.contentDocument.defaultView.removeEventListener('resize', EleResize._handleResize);
                    ele.removeChild(trigger);
                    delete ele.__resizeTrigger__;
                }
                delete  ele.__z_resizeListeners;
            }
        }
    }
}
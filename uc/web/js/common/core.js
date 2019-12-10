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
                    "icon":6,
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
            popLayer:function( url,data,modal_params){
                $.ajax({
                    url: url,
                    data: data,
                    type: "GET",
                    dataType: 'json',
                    success: function (res) {
                        if(res.code != 200 ){
                            common_ops.alert(res.msg);
                            return;
                        }
                        $("#pop_layer").html( res.data.content );
                        $('#pop_layer').modal( modal_params );
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

// 管理注册界面.
window.onerror = function(message, url, lineNumber,columnNo,error) {
    var data = {
        'message':message,
        'url':url,
        'error':error.stack
    };
    $.ajax({
        url:"/error/capture",
        type:'post',
        data:data,
        success:function(){

        }
    });
    return true;
};


/**
 * 菜单栏JS
 */
$('.menu-title a').each(function () {
    var icon = $(this).children('.iconfont');
    var dataUrl = $(this).attr('data-url');
    var url = document.URL;
    url.indexOf(dataUrl) > -1 ? icon.addClass('li_active') : icon.removeClass('li_active');
});

var resizeDiv = document.getElementById('left_menu');
$('.menu_bottom').click(function () {
    $('.menu-show-hide').each(function () {
        $(this).toggle();
    });
});
var lockSize = function () {
    resizeDiv.offsetWidth > 150 ? $('.menu-show').show().addClass('bounceInLeft animated'):'';
}
var closeSize = function () {
    resizeDiv.offsetWidth < 180 ? $('.menu-show').hide() : '';
}
function menuLock() {
    EleResize.off(resizeDiv, closeSize);
    $('.left_menu').width('190px');
    $('#merchant .chant_all .right_merchant .right_content').css('margin-left','190px');
    EleResize.on(resizeDiv,lockSize);
}
function menuClose() {
    EleResize.off(resizeDiv, lockSize);
    $('.left_menu').width('90px');
    $('#merchant .chant_all .right_merchant .right_content').css('margin-left','90px');
    EleResize.on(resizeDiv,closeSize);
}
$('.menu-title a').mouseover(function () {
    $('.left_menu').width() > 95 ?   $(this).children('.menu-tooltip').hide() : $(this).children('.menu-tooltip').show();
    $(this).children('.menu-tooltip').addClass('fadeIn animated');
})
$('.menu-title a').mouseout(function () {
    $(this).children('.menu-tooltip').hide();
})

// $(".menu_info_link").mouseover(function(event){
//    $('.menu_info_edit').height('190px')
//    $(".menu_info_link").each(function () {
//       $(".menu_info_edit").toggle();
//    });
// });

var $submenu = $('.submenu');
var $mainmenu = $('.mainmenu');
$submenu.hide();
$submenu.first().delay(400).slideDown(700);
$submenu.on('click','li', function() {
    $submenu.siblings().find('li').removeClass('chosen');
    $(this).addClass('chosen');
});
$mainmenu.on('click', 'li', function() {
    $(this).next('.submenu').slideToggle().siblings('.submenu').slideUp();
});
$mainmenu.children('li:last-child').on('click', function() {
    $mainmenu.fadeOut().delay(500).fadeIn();
});

$('.staff_tab').next().css('padding','20px');
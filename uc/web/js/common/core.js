;
// 所有基本对核心的处理都需要使用此组件.
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

// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
// 例子：
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
Date.prototype.Format = function(fmt)
{ //author: meizz
    var o = {
        "M+" : this.getMonth()+1,                 //月份
        "d+" : this.getDate(),                    //日
        "h+" : this.getHours(),                   //小时
        "m+" : this.getMinutes(),                 //分
        "s+" : this.getSeconds(),                 //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S"  : this.getMilliseconds()             //毫秒
    };
    if(/(y+)/.test(fmt))
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    for(var k in o)
        if(new RegExp("("+ k +")").test(fmt))
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
    return fmt;
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
;
var common_ops = {
    init:function(){
        // this.setMenuIconHighLight();
    },
    setMenuIconHighLight:function(){
        if( $(".box_left_nav .menu_list").size() < 1 ){
            return;
        }
        var pathname = window.location.pathname;
        var nav_name = null;

        if(  pathname.indexOf("/merchant/default") > -1 || pathname == "/merchant" || pathname == "/merchant/" ){
            nav_name = "dashboard";
        }

        if(  pathname.indexOf("/merchant/staff") > -1  ){
            nav_name = "staff";
        }

        if(  pathname.indexOf("/merchant/help") > -1  ){
            nav_name = "help";
        }

        if(  pathname.indexOf("/stat") > -1  ){
            nav_name = "stat";
        }

        if(  pathname.indexOf("/chat") > -1  ){
            nav_name = "chat";
        }

        if(  pathname.indexOf("/setting") > -1  ){
            nav_name = "setting";
        }

        if( nav_name == null ){
            return;
        }

        $(".box_left_nav .menu_list li."+nav_name).addClass("current");
    },
    buildMerchantUrl:function( path ,params){
        var url =  "/merchant" + path;
        var _paramUrl = '';
        if( params ){
            _paramUrl = Object.keys(params).map(function(k) {
                return [encodeURIComponent(k), encodeURIComponent(params[k])].join("=");
            }).join('&');
            _paramUrl = "?"+_paramUrl;
        }
        return url + _paramUrl

    },
    buildPicStaticUrl:function(bucket,img_key,params){
        bucket = bucket.replace(/zhyd_/g, "");
        bucket = bucket?bucket:"pic3";
        var url = "http://"+bucket+".s.360zhishu.cn/"+img_key;

        var width = params.hasOwnProperty("w")?params['w']:0;
        var height = params.hasOwnProperty("h")?params['h']:0;
        if( !width && !height ){
            return url;
        }

        if( params.hasOwnProperty('view_mode') ){
            url += "?imageView2/"+params['view_mode'];
        }else{
            url += "?imageView2/1";
        }

        if( width ){
            url += "/w/"+width;
        }

        if( height ){
            url += "/h/"+height;
        }
        url += "/interlace/1";
        return url;
    },
    'getRequest': function (key, default_value) {
        var url = location.search; //获取url中"?"符后的字串
        var theRequest = {};
        if (url.indexOf("?") !== -1) {
            var str = url.substr(1);
            var strs = str.split("&");
            for (var i = 0; i < strs.length; i++) {
                theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
            }
        }

        if(key) {
            return theRequest[key] ? theRequest[key] : default_value;
        }

        return theRequest;
    }
};

$(document).ready(function(){
    common_ops.init();
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
});

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

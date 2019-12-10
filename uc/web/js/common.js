;
var uc_common_ops = {
    init:function(){
        this.setMenuIconHighLight();
    },
    setMenuIconHighLight:function(){
        if( $(".menu-title .iconfont").length < 1 ){
            return;
        }

        var pathname = window.location.pathname,
            nav_name = null,
            uris = {
            'yonghuguanli'  : [
                '/staff/',
                '/action/',
                '/role/',
                '/department/'
            ],
            'liaotian'      : [
                '/merchant/chat/index',
                '/merchant/chat/download'
            ],
            'quanjushezhi'  : [
                '/company'
            ]
        };

        for(var index in uris) {
            for(var i = 0; i < uris[index].length; i++) {
                if(pathname.indexOf(uris[index][i]) > -1) {
                    nav_name = index;
                }
            }
        }

        if( nav_name == null ){
            return;
        }

        $(".menu-title .icon-"+nav_name).addClass("li_active");
    },
    buildUcUrl:function( path ,params) {
        var url = $(".hidden_val_wrap input[name=domain_app]").val() + path;
        var _paramUrl = '';
        if (params) {
            _paramUrl = Object.keys(params).map(function (k) {
                return [encodeURIComponent(k), encodeURIComponent(params[k])].join("=");
            }).join('&');
            _paramUrl = "?" + _paramUrl;
        }
        return url + _paramUrl;
    },
    buildPicStaticUrl:function(bucket,img_key,params){
        bucket = bucket ? bucket: "pic3";
        var config = {
            'hsh': {
                'http': 'http://cdn.static.test.jiatest.cn',
                'https': 'https://cdn.static.test.jiatest.cn'
            }
        };

        var url = config[bucket].http + '/' + img_key;

        var width = params && params.hasOwnProperty("w") ? params['w']:0;
        var height = params && params.hasOwnProperty("h") ? params['h']:0;
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
    getRequest: function (key, default_value) {
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

var common_ops_url = {
    buildUrl:function( path, params ){
        return uc_common_ops.buildUcUrl( path, params );
    }
};

$(document).ready(function(){
    uc_common_ops.init();
});
;
var merchant_common_ops = {
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
                'liaotian'      : [
                    '/merchant/chat/index',
                    '/merchant/chat/download'
                ],
                'quanjushezhi'  : [
                    '/merchant/overall/index',
                    '/merchant/overall/clueauto',
                    '/merchant/overall/breakauto',
                    '/merchant/overall/offline',
                    '/merchant/overall/code'
                ],
                'heimingdan'    : [
                    '/merchant/black/index'
                ],
                'fengge'        : [
                    '/merchant/style/index',
                    '/merchant/style/computer',
                    '/merchant/style/mobile',
                    '/merchant/style/newsauto',
                    '/merchant/style/reception',
                    '/merchant/style/video'
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

        $('.menu-title .icon-'+nav_name).addClass('li_active');
    },
    buildUCUrl:function (path, params) {
        var url = '/uc' + path;

        var _paramUrl = '';
        if( params ){
            _paramUrl = Object.keys(params).map(function(k) {
                return [encodeURIComponent(k), encodeURIComponent(params[k])].join("=");
            }).join('&');
            _paramUrl = "?"+_paramUrl;
        }

        return url + _paramUrl
    }
};


var common_ops_url = {
    buildUrl:function( path, params ){
        return merchant_common_ops.buildUCUrl( path, params );
    }
};

$(document).ready(function(){
    merchant_common_ops.init();
});
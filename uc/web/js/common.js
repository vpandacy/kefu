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
    }
};
// 每个模块要自己生成url.否则就无法处理.
var global_url_ops = {
    buildUcUrl:function (path, params) {
        if(!application_setting.app_name) {
            application_setting.app_name = 'uc';
        }

        var url = !application_setting.domains[application_setting.app_name]
            ? '/'
            : application_setting.domains[application_setting.app_name];

        if(application_setting.app_name != 'uc') {
            url += '/uc';
        }

        url += path;

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


$(document).ready(function(){
    uc_common_ops.init();
});
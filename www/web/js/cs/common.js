;
var cs_common_ops = {
    buildUCUrl:function (path, params) {
        var url = $(".hidden_val_wrap input[name=domain_uc]").val() + path;

        var _paramUrl = '';
        if( params ){
            _paramUrl = Object.keys(params).map(function(k) {
                return [encodeURIComponent(k), encodeURIComponent(params[k])].join("=");
            }).join('&');
            _paramUrl = "?"+_paramUrl;
        }

        return url + _paramUrl
    },
    buildKFCSurl:function(path, params){
        var url = $(".hidden_val_wrap input[name=domain_app]").val() + path;
        var _paramUrl = '';
        if (params) {
            _paramUrl = Object.keys(params).map(function (k) {
                return [encodeURIComponent(k), encodeURIComponent(params[k])].join("=");
            }).join('&');
            _paramUrl = "?" + _paramUrl;
        }
        return url + _paramUrl;
    }
};


var common_ops_url = {
    buildUrl:function( path, params ){
        return cs_common_ops.buildUCUrl( path, params );
    }
};
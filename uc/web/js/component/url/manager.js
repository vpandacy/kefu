;
// 主要管理各种url的生成规则.
var url_manager = {
    buildUcUrl:function( path ,params) {
        return global_url_ops.buildUcUrl(path, params);
    },
    buildMerchantUrl:function(path, params) {
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
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
            'kefu'  : [
                '/staff/',
                '/action/',
                '/role/',
                '/department/',
                '/log/'
            ],
            'liaotian'      : [
                '/merchant/chat/index',
                '/merchant/chat/download',
                '/merchant/overall/offline'
            ],
            'quanjushezhi'  : [
                '/company'
            ]
        };

        for(var index in uris) {
            for(var i = 0; i < uris[index].length; i++) {
                if(pathname.indexOf(uris[index][i]) > -1) {
                    nav_name = index;
                    break;
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
    // 静态资源信息.
    buildStaticUrl:function( path ,params) {
        var url = $(".hidden_val_wrap input[name=domain_static]").val() + path;
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
        var config = $('[name=domain_cdn]').val();

        if(config) {
            config = JSON.parse(config);
        }

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
    },

    // 这里生成统一的layui 数据表格的配置. 方便统一管理
    buildLayuiTableConfig: function (params) {
        return Object.assign({},{
            limit: 15,  // 分页信息.
            page: {     // 分页的模板.
                layout: ['prev', 'page', 'next', 'first', 'last' ,'skip','count']
            },
            method: 'POST',
            // 规定返回的信息.
            response: {
                statusCode: 200,
                countName: 'count',
                dataName: 'data',
                statusName: 'code'
            }
        }, params);
    },
    updateNews: function (id) {
        $.ajax({
            url: common_ops_url.buildUrl("/news/ops"),
            type: "POST",
            data: {
                id: id,
                act: "has_read"
            },
            dataType: "json",
            success: function (res) {
            }
        });
    },
    avatarMenu:function(){
        var that = this;
        $.ajax({
            url: common_ops_url.buildUrl("/default/menu"),
            dataType: "json",
            success: function (res) {
                if (res.code != 200 ) {
                    return;
                }

                $(".main-header .user-menu").html( res.data.content );
            }
        });
    }
};

var common_ops_url = {
    buildUrl:function( path, params ){
        return uc_common_ops.buildUcUrl( path, params );
    },
    buildCdnPicSUrl: function (bucket, img_key, params) {
        return uc_common_ops.buildPicStaticUrl(bucket, img_key, params);
    }
};

$(document).ready(function(){
    uc_common_ops.init();
});

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
    // resizeDiv.offsetWidth > 150 ? $('.menu-show').show():'';
    // resizeDiv.offsetWidth > 150 ? $('.menu-show').show().addClass('bounceInLeft animated'):'';
};

var closeSize = function () {
    // resizeDiv.offsetWidth < 180 ? $('.menu_min_logo').show() : '';
    // resizeDiv.offsetWidth < 180 ? $('.menu-show').hide() : '';
};


function menuLock() {
    EleResize.off(resizeDiv, closeSize);
    $('.left_menu').width('190px');
    $('#merchant .chant_all .right_merchant .right_content').css('margin-left','190px');
    EleResize.on(resizeDiv,lockSize);
}

// function menuClose() {
//     EleResize.off(resizeDiv, lockSize);
//     $('.left_menu').width('90px');
//     $('#merchant .chant_all .right_merchant .right_content').css('margin-left','90px');
//     EleResize.on(resizeDiv,closeSize);
// }

$('.menu-title a').mouseover(function () {
    $('.left_menu').width() > 95 ?   $(this).children('.menu-tooltip').hide() : $(this).children('.menu-tooltip').show();
    $(this).children('.menu-tooltip').addClass('fadeIn animated');
});

$('.menu-title a').mouseout(function () {
    $(this).children('.menu-tooltip').hide();
});

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
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
                'yonghuguanli'  : [
                    '/merchant/user',
                ],
                'liaotian'      : [
                    '/merchant/chat/index',
                    '/merchant/chat/download',
                    '/merchant/overall/offline'
                ],
                'quanjushezhi'  : [
                    '/merchant/overall/index',
                    '/merchant/overall/clueauto',
                    '/merchant/overall/breakauto',
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
                    '/merchant/style/video',
                    '/merchant/style/setting'
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
    buildMerchantUrl:function(path, params){
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
    // 这里生成统一的layui 数据表格的配置. 方便统一管理
    buildLayuiTableConfig: function (params) {
        return Object.assign({},{
            limit: 15,  // 分页信息.
            page: {     // 分页的模板.
                layout: ['prev', 'page', 'next', 'first', 'last' ,'skip']
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
    }
};


var common_ops_url = {
    buildUrl:function( path, params ){
        return merchant_common_ops.buildUCUrl( path, params );
    },
    buildCdnPicSUrl: function (bucket, img_key, params) {
        return merchant_common_ops.buildPicStaticUrl(bucket, img_key, params);
    }
};

$(document).ready(function(){
    merchant_common_ops.init();
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
    resizeDiv.offsetWidth > 90 ? $('.menu_max_logo').show() : '';
    resizeDiv.offsetWidth > 90 ? $('.menu_min_logo').hide() : '';
    resizeDiv.offsetWidth > 150 ? $('.menu-show').show().addClass('bounceInLeft animated'):'';
};

var closeSize = function () {
    resizeDiv.offsetWidth < 180 ? $('.menu_min_logo').show() : '';
    resizeDiv.offsetWidth < 180 ? $('.menu_max_logo').hide(): '';
    resizeDiv.offsetWidth < 180 ? $('.menu-show').hide() : '';
};

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
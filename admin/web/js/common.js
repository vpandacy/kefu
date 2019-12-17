;
var admin_common_ops = {
    init: function () {
        this.eventBind();
        this.setIframeTab();
        this.avatarMenu();
    },
    eventBind: function () {
        var that = this;
        this.setMenuIconHighLight();
    },
    setMenuIconHighLight: function () {

        if ($(".main-sidebar .sidebar-menu li.treeview").length < 1) {
            return;
        }

        var pathname = window.location.pathname;

        var nav_name = null;


        if (pathname.indexOf("/customer/") > -1) {
            nav_name = "customer";
        }
        if (pathname.indexOf("/platform/") > -1) {
            nav_name = "platform";
        }
        if (pathname.indexOf("/customer/apply/") > -1 || pathname.indexOf("/customer/transfer/") > -1 || pathname.indexOf("/platform/apply/") > -1 || pathname.indexOf("/platform/refund/") > -1 || pathname.indexOf("/platform/task/") > -1) {
            nav_name = "works";
        }

        if (pathname.indexOf("/staff/") > -1 || pathname.indexOf("/department/") > -1
            || pathname.indexOf("/role/") > -1) {
            nav_name = "staff";
        }

        if (pathname.indexOf("/info-credit/") > -1 || pathname.indexOf("/info-customer/") > -1 || pathname.indexOf("/info/") > -1) {
            nav_name = "info";
        }

        if (pathname.indexOf("/financial/") > -1) {
            nav_name = "financial";
        }
        if (pathname.indexOf("/action/") > -1) {
            nav_name = "rbac";
        }
        if (nav_name == null) {
            return;
        }

        $(".main-sidebar .sidebar-menu li.menu_" + nav_name).addClass("active");

        //继续高亮子菜单
        $(".sidebar-menu .treeview-menu li a").each(function () {
            var link_url = $(this).attr("href");
            if (link_url.indexOf(pathname) > -1) {
                $(this).parent("li").addClass("active");
                return false;
            }
        });
    },
    buildUrl: function (path, params) {
        var url = $(".hidden_val_wrap input[name=domain]").val() + path;
        var _paramUrl = '';
        if (params) {
            _paramUrl = Object.keys(params).map(function (k) {
                return [encodeURIComponent(k), encodeURIComponent(params[k])].join("=");
            }).join('&');
            _paramUrl = "?" + _paramUrl;
        }
        return url + _paramUrl
    },
    setIframeTab: function () {
        return;
    },
    digitUppercase:function( n ){
        var fraction = ['角', '分'];
        var digit = [
            '零', '壹', '贰', '叁', '肆',
            '伍', '陆', '柒', '捌', '玖'
        ];
        var unit = [
            ['元', '万', '亿'],
            ['', '拾', '佰', '仟']
        ];
        var head = n < 0 ? '欠' : '';
        n = Math.abs(n);
        var s = '';
        for (var i = 0; i < fraction.length; i++) {
            s += (digit[Math.floor(n * 10 * Math.pow(10, i)) % 10] + fraction[i]).replace(/零./, '');
        }
        s = s || '整';
        n = Math.floor(n);
        for (var i = 0; i < unit[0].length && n > 0; i++) {
            var p = '';
            for (var j = 0; j < unit[1].length && n > 0; j++) {
                p = digit[n % 10] + unit[1][j] + p;
                n = Math.floor(n / 10);
            }
            s = p.replace(/(零.)*零$/, '').replace(/^$/, '零') + unit[0][i] + s;
        }
        return head + s.replace(/(零.)*零元/, '元')
            .replace(/(零.)+/g, '零')
            .replace(/^整$/, '零元整');
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
    },
};

var common_ops_url = {
    buildUrl:function( path, params ){
        return admin_common_ops.buildUrl( path, params );
    }
};

$(document).ready(function () {
    admin_common_ops.init();
});

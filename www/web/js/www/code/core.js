;
/**
 * FLIE:点击上传,触发隐藏input[type=file]的点击事件
 * changeFile:File值改变的事件
 * @type {{flie: inputFlie.flie, changeFile: inputFlie.changeFile}}
 */
var inputFlie = {
    flie : function () {
        $('#inputFile').click();
    },
    changeFile : function  () {
        // console.log('test')
    }
};

$(function () {
    /**
     * 控制右下角聊天切换状态
     */
    $('.show-hide').css({display:'none'});
    $('.show-hide-min').click(function () {
        $('.show-hide-min').css({display:'none'});
        $('.show-hide').css({display:'block'});
    });

    $('.show-hide-max').click(function () {
        $('.show-hide-min').css({display:'block'});
        $('.show-hide').css({display:'none'});
    });
    /**
     * 打开新窗口聊天页面
     */
    $('.icon-fenxiang').click(function () {
        var code = $('#online_kf').attr('data-code'),
            msn  = $('#online_kf').attr('data-sn');

        // 这里要动态生成一下.
        window.open('http://www.kefu.dev.hsh568.cn/'+ msn +'/code/online?code=' + code, 'newindow', 'height=610,width=810,top=150,left=1000,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no')
        $('.show-hide-min').css({display:'block'});
        $('.show-hide').css({display:'none'});
    });

    $('.icon-wenjian').click(function () {
        inputFlie.flie();
    });

    /**
     * 定时加载图标显示隐藏
     */
    $('.line').click(function () {
        $('.icon-jiazaizhong').fadeIn();
        setTimeout(function () {
            $('.icon-jiazaizhong').fadeOut();
        },3000);//afterbegin
        // 显示聊天记录
        setTimeout(function () {
            var html  = ' <div class="content-message">\n' +
                '             <div class="message-img">\n' +
                '             <img class="logo" src="/images/www/code/test.png">\n' +
                '         </div>\n' +
                '               <div class="message-info">\n' +
                '               <div class="message-name-date"><span>楠楠</span><span class="date">10:57:56</span></div>\n' +
                '               <div class="message-message">您好，请问您的电话或微信是多少呢？稍后把详细资料、优化政策、产品图册，利润分析等发到您手机上，以便您更好的了解！</div>\n' +
                '           </div>\n' +
                '        </div>'
            for (let i = 0 ; i< 2; i ++)  {
                document.getElementsByClassName('online-content')[0].insertAdjacentHTML('afterbegin',html)
            }
        },3000);
        // 滚动条回到顶部
        setTimeout(function () {
            document.getElementsByClassName('online-content')[0].scrollTop = 0;
        },3500);
        // document.documentElement.scrollTop= window.pageYOffset = document.body.scrollTop = 0;
    });
    $('.icon-jianqie').click(function () {
        $('.online-cover').show();
        $('.capture-dialog').show();
    });
    $('.icon-guanbi').click(function () {
        $('.online-cover').hide();
        $('.capture-dialog').hide();
    });
});

$(document).ready(function(){
    /**
     * 表情
     * */
    sdEditorEmoj.Init(emojiconfig);
    sdEditorEmoj.setEmoji({type:'div',id:"content"});

    /**
     * 截图初始化
     */
    $().ready(function(){
        $('#moreparams').hide();

        $('#captureselectSize').click( function(){
            var autoFlag = $("#captureselectSize").attr("checked")=="checked" ? 1 : 0;
            if(autoFlag == 1){
                $('#moreparams').show();
            }
            else{
                $('#moreparams').hide();
            }
        });
        $('#getimagefromclipboard').click( function(){
            $('#posdetail').hide();
        });
        $('#showprewindow').click( function(){
            $('#posdetail').hide();
        });
        $('#fullscreen').click( function(){
            $('#posdetail').hide();
        });
        $('#specificarea').click( function(){
            $('#posdetail').show();
        });

        $('#showprewindow').click();
        $('#autoupload').click();
        $('#btnUpload').hide();
        Init();
    });
});
// 用于前台的数据存储.
var chat_storage = {
    // 封装自己的读取和存储函数.
    setItem: function (key, value) {
        if(typeof value == 'object') {
            value = JSON.stringify(value);
        }

        if(window.localStorage) {
            localStorage.setItem(key, value);
        }

        // 存到cookie中.
        if(parent.document) {
            this._setCookie(key, value);
        }

        return true;
    },
    getItem: function (key, default_value) {
        var value = null;

        if(window.localStorage) {
            value = localStorage.getItem(key);
        }

        if(!value && parent.document ) {
            value = this._getCookie(key);
        }

        if(!value) {
            return default_value;
        }

        // 自动解析数据.
        try{
            return JSON.parse(value);
        }catch (e) {
            return value;
        }
    },
    // 获取cookie信息.
    _getCookie: function(name) {
        var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
        if (parent.document.cookie.match(reg)) {
            arr = parent.document.cookie.match(reg);
            return unescape(arr[2]);
        }

        return '';
    },
    _setCookie: function (name, value) {
        var now = new Date();
        // 先存个十年.
        now.setTime(now.getTime()+(3650 * 24 * 3600000));
        parent.document.cookie=name+ "=" + escape(value) + ";path=/;expires="+now.toGMTString()
        return true;
    }
};
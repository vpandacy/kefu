;
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
        // 这里要动态生成一下.
        var data = JSON.parse( $(".hidden_wrapper input[name=params]").val() );
        window.open(data['tab_url'], 'newindow', 'height=610,width=810,top=150,left=1000,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no')
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
});
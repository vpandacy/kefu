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

// 关于前台聊天的基本功能.
var chat = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        // 发送消息.
        $('#content').on('keydown', function (event) {
            // 不等于回车的时候.
            if(event.keyCode != 13) {
                return true;
            }
            // 修改掉其他事件
            event.preventDefault();
            var msg = $('#content').text();

            if(msg.length <= 0) {
                return false;
            }

            var date = new Date();
            var time_str = [
                date.getHours(),
                date.getMinutes(),
                date.getSeconds()
            ].map(function (value) {
                return value < 10 ? '0' + value : value;
            }).join(':');

            var div = document.createElement('div');

            div.innerHTML = [
                '<div class="content-message message-my">',
                '    <div class="message-info">',
                '        <div class="message-name-date name-date-my"><span class="date">',time_str,'</span><span class="message-name">我</span></div>',
                '        <div class="message-message message-message-my">',msg,'</div>',
                '    </div>',
                '</div>'
            ].join("")

            $('.online-content').append(div);

            $('#content').text('');

            var total_height = $('.online-content')[0].scrollHeight,
                current_height = $('.online-content').height() + $('.online-content').scrollTop();


            // 当前高度大于等于总的高度  就移动下来. 增加10像素的容错率
            if(current_height <= total_height - $(div).height() - 10) {
                return false;
            }

            // 每次发送完消息滚动到最底部.
            $('.online-content').scrollTop(total_height);
            return false;
        });
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
        window.open('http://www.kefu.dev.hsh568.cn/'+ msn +'/code/online?code=' + code, 'newindow', 'height=610,width=810,top=150,left=550,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no')
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

    chat.init();
});
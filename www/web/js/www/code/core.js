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
    $('.icon-_DYGYxinyemiandakai').click(function () {
        // 这里要动态生成一下.
        var data = JSON.parse( $(".hidden_wrapper input[name=params]").val() );
        // 主动关闭聊天框.
        window.ws.close();
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
    $('.from-button-message').click(()=>{
        let fromData = ['name','mobile','wechat','message']
        let param = {}
        fromData.forEach((value, index, array) => {
            param[value] = $("#online-from [name="+value+"]").val()
        })
        param['msn'] = JSON.parse(localStorage.getItem("serverInfo")).msn
        param['code'] = JSON.parse(localStorage.getItem("serverInfo")).code
        $.ajax({
            url:'/code/leave',
            type:'post',
            data:param,
            dataType: 'json',
            success: res => {
                res.code != 200 ?  $.message({message:res.msg, type:'error'}) : $.message('提交成功');
            }
        });
    });

    // ws_connect代表链接过来了
    // hello 代表初次链接成功了.系统返回的返回消息.
    // assign_kf 这个是链接成功过后  系统分配了客服.
    // change_kf 代表是更换客服.
    // reply 代表客服回复消息过来了．
    // 服务端的close_guest关闭客服 则触发
    window.ws.addEventListener('message', event => {
        console.log(event.data);
        var data = JSON.parse(event.data);
        console.log(event.data)
            switch (data.cmd) {
                case 'ws_connect'||'hello':
                    $('.ws_flag').text('正在连接客服...')
                    break;
                case 'assign_kf'||'change_kf'||'reply':
                    $('.ws_flag').text('连接成功')
                    break;
                case 'close_guest':
                    $('#online-from').show()
                    $('.ws_flag').text('连接关闭')
                    break;
            }
    });
});

$(document).ready(function(){
    /**
     * 表情
     * */
    sdEditorEmoj.Init(emojiconfig);
    sdEditorEmoj.setEmoji({type:'div',id:"content"});
});
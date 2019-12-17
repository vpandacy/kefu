;
$(document).ready(function () {
    /**
     * 表情
     * */
    sdEditorEmoj.Init(emojiconfig);
    sdEditorEmoj.setEmoji({type: 'input', id: "content"});
    $('.icon-zaixianzixun').click(function () {
        $('.waponline-max').removeClass('dis_none');
        $('.icon-zaixianzixun').addClass('dis_none');
    });

    $('.icon-zuojiantou').click(function () {
        $('.waponline-max').addClass('dis_none');
        $('.icon-zaixianzixun').removeClass('dis_none');
    })


    var data = JSON.parse( $(".hidden_wrapper input[name=params]").val() );
    // 不主动断开.
    if(data.auto_disconnect == 0) {
        return;
    }

    var auto_disconnect = parseInt(data.auto_disconnect);
    var interval = setInterval(function () {
        auto_disconnect -= 1;
        if(auto_disconnect <= 0) {
            clearInterval(interval);
            $('.message span').text('由于您长时间没有对话，系统已经关闭您的会话');
            // 主动关闭聊天.
            window.ws.close();
        }
    }, 1000);
});

;
var socket = null;

var mobile_ops ={
    init: function () {
        this.eventBind();

        // 这里要保存用户的信息.和收集用户的一些数据.
        var data = {
            uuid: $('#wapOnline').attr('data-uuid'),
            msn : $('#wapOnline').attr('data-sn'),
            code: $('#wapOnline').attr('data-code')
        };
        // 开始存储关键信息.
        ChatStorage.setItem('hshkf', data);
        // 开始获取一些基本信息.
        socket = this.initSocket();
    },
    eventBind: function () {
        var that = this;
        // 发送消息.

        $('.submit').on('click', function (event) {
            event.preventDefault();

            var msg = $('[name=message]').val();

            if(msg.length <= 0) {
                return false;
            }

            socket.send(that.buildMsg('chat',{
                content: msg
            }));

            var date = new Date();
            var time_str = [
                date.getHours(),
                date.getMinutes(),
                date.getSeconds()
            ].map(function (value) {
                return value < 10 ? '0' + value : value;
            }).join(':');

            var div = document.createElement('div');
            div.style.textAlign = "right";
            div.innerHTML = [
                '<div class="content-message message-my">',
                '    <div class="message-info my-message-info">',
                '        <div class="message-name-date name-date-my"><span class="date">',time_str,'</span><span class="message-name">我</span></div>',
                '        <div class="message-message message-message-my">',msg,'</div>',
                '    </div>',
                '</div>'
            ].join("")

            $('#wapOnline .content').append(div);

            $('[name=message]').val('');

            mobile_ops.scrollToBottom();
        });
    },
    initSocket: function () {
        var host = $('[name=host]').val();
        if(!host) {
            // 这里是非法的客服.
            return false;
        }
        // 使用socket来链接.
        var socket = new WebSocket('ws://' + host);

        // 打开websocket信息.
        socket.addEventListener('open', function () {
            var user = ChatStorage.getItem('hshkf');
            // 初次建立链接.
            socket.send(mobile_ops.buildMsg('guest_in', {
                ua: navigator.userAgent,
                url: parent.location.href,
                referer: parent.document.referrer,
                msn: user.msn,
                code: user.code
            }));
        });

        // 接收websocket返回的信息.
        socket.addEventListener('message', function (event) {
            var data = JSON.parse(event.data);

            if(data.cmd == 'ping') {
                return socket.send(mobile_ops.buildMsg('pong'));
            }

            // 这里要处理主要业务的逻辑.
            // 分配客服了.
            if(data.cmd == 'assign_kf' && data.code == 200) {
                var user = ChatStorage.getItem('hshkf');

                user.cs  = {
                    cs_sn: data.data.cs_sn,
                    nickname: data.data.nickname,
                    avatar: data.data.avatar
                };

                $('.title').text(user.cs.nickname);
                ChatStorage.setItem('hshkf', user);
            }

            // 这里是聊天信息.
            if(data.cmd == 'chat' && data.code == 200) {
                var user = ChatStorage.getItem('hshkf');
                $('#wapOnline .content').append(mobile_ops.buildCsMsg(user.cs.nickname, user.cs.avatar, data.data.content))
                mobile_ops.scrollToBottom();
            }
        });

        // 关闭websocket发送的信息.
        socket.addEventListener('close', function () {

        });

        // 这里是websocket发生错误的.信息.
        socket.addEventListener('error', function () {

        });

        return socket;
    },
    buildMsg: function (cmd, data) {
        var user = ChatStorage.getItem('hshkf', {}),
            send_data = {};

        send_data.cmd = cmd;
        send_data.data = {};

        if(data) {
            send_data.data = data;
        }

        send_data.data.f_id = user.uuid;
        send_data.data.t_id = user.cs ? user.cs.cs_sn : '';

        return JSON.stringify(send_data);
    },
    buildCsMsg: function (nickname, avatar, msg) {
        var date = new Date();

        var time_str = [
            date.getHours(),
            date.getMinutes(),
            date.getSeconds()
        ].map(function (value) {
            return value < 10 ? '0' + value : value;
        }).join(':');

        return [
            '<div class="content-message">',
            '   <div class="message-img">',
            '       <img class="logo" src="', avatar, '">',
            '   </div>',
            '   <div class="message-info">',
            '       <div class="message-name-date"><span>', nickname, '</span><span class="date">', time_str, '</span></div>',
            '       <div class="message-message">', msg, '</div>',
            '   </div>',
            '</div>'
        ].join("");
    },
    scrollToBottom: function () {
        var total_height = $('#wapOnline .content')[0].scrollHeight;
        $('#wapOnline .content').scrollTop(total_height);
    }
};

$(document).ready(function () {

    mobile_ops.init();
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
});

;
var socket = null;
// 关于前台聊天的基本功能.
var chat = {
    init: function () {
        this.eventBind();

        // 这里要保存用户的信息.和收集用户的一些数据.
        var data = {
            uuid: $('#online_kf').attr('data-uuid'),
            msn : $('#online_kf').attr('data-sn'),
            code: $('#online_kf').attr('data-code')
        };
        // 开始存储关键信息.
        chat_storage.setItem('hshkf', data);
        // 开始获取一些基本信息.
        socket = this.initSocket();
    },
    eventBind: function () {
        var that = this;
        // 发送消息.
        $('#content').on('keydown', function (event) {
            // 不等于回车的时候.
            if(event.keyCode == 13 && event.shiftKey || event.keyCode != 13) {
                return true;
            }
            // 修改掉其他事件
            event.preventDefault();
            that.send();
            return false;
        });

        $('.submit-button').on('click', function (event) {
            event.preventDefault();
            that.send();
        });
    },
    send: function () {
        var msg = $('#content').text();

        if(msg.length <= 0) {
            return false;
        }

        socket.send(this.buildMsg('chat',{
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
            '    <div class="message-info">',
            '        <div class="message-name-date name-date-my"><span class="date">',time_str,'</span><span class="message-name">我</span></div>',
            '        <span class="message-message message-message-my">',msg,'</span>',
            '    </div>',
            '</div>'
        ].join("")

        $('.online-content').append(div);

        $('#content').text('');

        chat.scrollToBottom();
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
            var user = chat_storage.getItem('hshkf');
            // 初次建立链接.
            socket.send(chat.buildMsg('guest_in', {
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
                return socket.send(chat.buildMsg('pong'));
            }

            // 这里要处理主要业务的逻辑.
            // 分配客服了.
            if(data.cmd == 'assign_kf' && data.code == 200) {
                var user = chat_storage.getItem('hshkf');

                user.cs  = {
                    cs_sn: data.data.cs_sn,
                    nickname: data.data.nickname,
                    avatar: data.data.avatar
                };

                chat_storage.setItem('hshkf', user);
            }

            // 这里是聊天信息.
            if(data.cmd == 'chat' && data.code == 200) {
                var user = chat_storage.getItem('hshkf');
                $('.online-content').append(chat.buildCsMsg(user.cs.nickname, user.cs.avatar, data.data.content))
                chat.scrollToBottom();
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
        var user = chat_storage.getItem('hshkf', {}),
            send_data = {};

        send_data.cmd = cmd;

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
            '       <span class="message-message">', msg, '</span>',
            '   </div>',
            '</div>'
        ].join("");
    },
    scrollToBottom: function () {
        var total_height = $('.online-content')[0].scrollHeight;
        $('.online-content').scrollTop(total_height);
    }
};

$(document).ready(function () {
    chat.init();
});
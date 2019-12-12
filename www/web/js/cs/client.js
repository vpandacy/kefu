;
var socket = null;

var client = {
    init: function () {
        this.eventBind();
        socket = this.initSocket();
    },
    eventBind: function() {
        var that = this;

        $('.sumbit-input').on('keydown', function (event) {
            if(event.keyCode == 13 && event.shiftKey || event.keyCode != 13) {
                return true;
            }

            event.preventDefault();

            // 这里准备提交
            var msg = $('.sumbit-input').text();

            if(!msg || msg.length < 0) {
                return false;
            }

            that.send(msg);
        });

        $('.sumbit').on('click', function () {
            var msg = $('.sumbit-input').text();

            if(!msg) {
                return false;
            }
            that.send(msg);
        });
    },
    send: function(msg) {
        socket.send(client.buildMsg('chat', {
            msg: msg
        }));

        var date = new Date();

        var time_str = [
            date.getHours(),
            date.getMinutes(),
            date.getSeconds()
        ].map(function (value) {
            return value < 10 ? '0' + value : value;
        }).join(':');

        // 清空掉.然后在将这个展示到对应的消息上去.
        $('.sumbit-input').text('');

        // 添加新消息进去.
        $('.exe-content-history').append([
            '<div class="content-message message-my">',
            '   <div class="message-info">',
            '      <div class="message-name-date name-date-my">',
            '          <span class="date">',time_str,'</span>',
            '          <span class="message-name">我</span>',
            '      </div>',
            '      <span class="message-message message-message-my">',msg,'</span>',
            '   </div>',
            '</div>'
        ].join(""));

        client.scrollToBottom();
    },
    initSocket: function () {
        // 使用socket来链接.
        var socket = new WebSocket('ws://192.168.117.122:8282');

        // 打开websocket信息.
        socket.addEventListener('open', function () {
            // 先定义一个不同的事件.后面在根据不同的定义不同的内容.
            socket.send(client.buildMsg('guest_in_cs', {
                cs_sn: $('[name=cs_sn]').val()
            }));
        });

        // 接收websocket返回的信息.
        socket.addEventListener('message', function (event) {
            var data = JSON.parse(event.data);
            if(data.cmd == 'assign_kf') {
                var user = {
                    customer: data.data.customer,
                    avatar: data.data.avatar,
                    nickname: data.data.nickname
                };
                localStorage.setItem('user', JSON.stringify(user));
            }

            if(data.cmd == 'ping') {
                socket.send(client.buildMsg('pong'))
            }

            if(data.cmd == 'chat') {
                // 这里要组装数据.
                // 获取游客的信息.
                var user = localStorage.getItem('user');

                user = JSON.parse(user);

                $('.exe-content-history').append(client.buildCustomerMsg(user.nickname, user.avatar, data.data.msg));

                client.scrollToBottom();
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
        var user = localStorage.getItem('user'),
            send_data = {};

        if(!user) {
            user = '{}';
        }

        user = JSON.parse(user);

        send_data.cmd = cmd;

        if(data) {
            send_data.data = data;
            send_data.form = $('[name=cs_sn]').val();
            send_data.to = user.customer ? user.customer : '';
        }

        return JSON.stringify(send_data);
    },
    buildCustomerMsg: function (nickname, avatar, msg) {
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
            '       <img class="logo" src="', avatar ,'">',
            '   </div>',
            '   <div class="message-info">',
            '       <div class="message-name-date"><span>',nickname,'</span><span class="date">', time_str ,'</span></div>',
            '       <span class="message-message">',msg,'</span>',
            '   </div>',
            '</div>'
        ].join("");
    },
    scrollToBottom: function () {
        var height = $('.exe-content-history')[0].scrollHeight;

        $('.exe-content-history').scrollTop(height);
    }
};


$(document).ready(function () {
    client.init();
});
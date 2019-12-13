;
var socket = null;
// 关于前台聊天的基本功能.
var kf_ws_service = {
    ws: null,
    connect:function( host ){
        this.ws = new WebSocket('ws://' + host);
        //这个事件应该标注 状态 已连接（绿色）
        this.ws.addEventListener('open', function () {
            chat_ops.handlerMessage( { "cmd":"ws_connect" } );
        });
        // 接收websocket返回的信息.
        this.ws.addEventListener('message', function (event) {
            var data = JSON.parse(event.data);
            chat_ops.handlerMessage( data );
        });

        // 关闭websocket发送的信息.
        this.ws.addEventListener('close', function () {
            //关闭
        });

        // 这里是websocket发生错误的.信息.
        this.ws.addEventListener('error', function () {
            //错误要把信息发回到监控中心
        });
    },
    socketSend: function (msg) {
        this.ws.send(JSON.stringify(msg));
    }
};
var chat_ops = {
    init: function () {
        // 这里要保存用户的信息.和收集用户的一些数据.
<<<<<<< ec9f3a243f6563b9f865f9c480f8dba2d72ed697
        var data = {
            uuid: $('#online_kf').attr('data-uuid'),
            msn : $('#online_kf').attr('data-sn'),
            code: $('#online_kf').attr('data-code')
        };
        // 开始存储关键信息.
        ChatStorage.setItem('hshkf', data);
        // 开始获取一些基本信息.
        socket = this.initSocket();
=======
        this.data = JSON.parse( $(".hidden_wrapper input[name=params]").val() );
        this.eventBind();
        this.initSocket();
>>>>>>> guowei -- 把代维的扫码做一下
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
<<<<<<< ec9f3a243f6563b9f865f9c480f8dba2d72ed697
        var msg = $('#content').html();

=======
        var msg = $('#content').text();
>>>>>>> guowei -- 把代维的扫码做一下
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
            '        <div class="message-message message-message-my">',msg,'</div>',
            '    </div>',
            '</div>'
        ].join("")

        $('.online-content').append(div);

        $('#content').html('');

        chat.scrollToBottom();
    },
    initSocket: function () {
<<<<<<< ec9f3a243f6563b9f865f9c480f8dba2d72ed697
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
                var user = ChatStorage.getItem('hshkf');

                user.cs  = {
                    cs_sn: data.data.cs_sn,
                    nickname: data.data.nickname,
                    avatar: data.data.avatar
                };

                $('#pc-online .header-left span').text(user.cs.nickname);
                ChatStorage.setItem('hshkf', user);
            }

            // 这里是聊天信息.
            if(data.cmd == 'chat' && data.code == 200) {
                var user = ChatStorage.getItem('hshkf');
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
=======
        kf_ws_service.connect( this.data['ws'] );
>>>>>>> guowei -- 把代维的扫码做一下
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
        var total_height = $('.online-content')[0].scrollHeight;
        $('.online-content').scrollTop(total_height);
    },
    handlerMessage:function( data ){
        var that = this;
        switch (data['cmd']) {
            case "ping":
                kf_ws_service.socketSend({ "cmd":"pong" });
                break;
            case "ws_connect":
                kf_ws_service.socketSend(chat.buildMsg('guest_in', {
                    ua: navigator.userAgent,
                    url: parent.location.href,
                    referer: parent.document.referrer,
                    msn: user.msn,
                    code: user.code
                }));
                break;
        }
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
            }

            chat_storage.setItem('hshkf', user);
        }

        // 这里是聊天信息.
        if(data.cmd == 'chat' && data.code == 200) {
            var user = chat_storage.getItem('hshkf');
            $('.online-content').append(chat.buildCsMsg(user.cs.nickname, user.cs.avatar, data.data.msg))
            chat.scrollToBottom();
        }
    }
};

$(document).ready(function () {
    chat_ops.init();
});
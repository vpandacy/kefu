;
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
            //错误要把信息发回到监控中心，并且是不是要重连几次，不行就关闭了
        });
    },
    socketSend: function (msg) {
        this.ws.send( JSON.stringify(msg) );
    }
};
var chat_ops = {
    init: function () {
        // 这里要保存用户的信息.和收集用户的一些数据.
        this.data = JSON.parse( $(".hidden_wrapper input[name=params]").val() );
        this.eventBind();
        this.initSocket();
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
        var msg = $('#content').html();
        if(msg.length <= 0) {
            return false;
        }

        kf_ws_service.socketSend( this.buildMsg('chat',{
            'content': msg
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
        this.scrollToBottom();
    },
    initSocket: function () {
        kf_ws_service.connect( this.data['ws'] );
    },
    buildMsg: function (cmd, data) {
        data['f_id'] = this.data['uuid'];
        data['msn'] = this.data['msn'];
        data['code'] = this.data['code'];
        var params = {
            cmd:cmd,
            data:data
        };
        return params;
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
                var params = {
                    ua: navigator.userAgent,
                    land: parent.location.href,
                    rf: parent.document.referrer,
                };
                kf_ws_service.socketSend( that.buildMsg('guest_in',params ));
                break;
            case "assign_kf":
                that.data['t_id'] = data.data['sn'];
                that.data['t_name'] = data.data['name'];
                that.data['t_avatar'] = data.data['avatar'];
                break;
            case "change_kf":
                that.data['t_id'] = data.data['sn'];
                that.data['t_name'] = data.data['name'];
                that.data['t_avatar'] = data.data['avatar'];
                break;
            case "reply":
                $('.online-content').append( that.buildCsMsg(that.data['t_name'], that.data['t_avatar'], data.data.content) );
                that.scrollToBottom();
                break;
        }
    }
};

$(document).ready(function () {
    chat_ops.init();
});
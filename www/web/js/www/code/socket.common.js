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
            console.dir('close');
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
    // 这里要保存用户的信息.和收集用户的一些数据.
    init: function () {
        this.data = JSON.parse( $(".hidden_wrapper input[name=params]").val() );
        localStorage.setItem('serverInfo',JSON.stringify(this.data))
        this.eventBind();
        this.initSocket();
    },
    // 監聽發送消息鍵盤事件
    eventBind: function () {
        var that = this;
        // 鍵盤事件.
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
    // 發送消息
    send: function () {
        var msg = $('#content').html();
        if(msg.length <= 0) {
            return false;
        }
        // 发送动作为chat.
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
            '<div class="content-message online-my-message">\n' +
            '     <div class="message-info">\n' +
            '     <div class="message-name-date"><span class="date">',time_str,'</span><span>我</span></div>\n' +
            '   <div class="message-message">',msg,'</div>\n' +
            '  </div>\n' +
            '</div>'
        ].join("")

        $('.message').append(div);

        $('#content').html('');
        this.scrollToBottom();
    },
    // 初始化webScoket
    initSocket: function () {
        kf_ws_service.connect( this.data['ws'] );
    },
    // 封裝客戶端消息數據
    buildMsg: function (cmd, data) {
        data['f_id'] = this.data['uuid'];
        data['msn'] = this.data['msn'];
        data['code'] = this.data['code'];
        if( this.data.hasOwnProperty("t_id") ){
            data['t_id'] = this.data['t_id'];
        }
        var params = {
            cmd:cmd,
            data:data
        };
        return params;
    },
    // 封裝服務端消息數據
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
            '<div class="content-message">\n' +
            '        <div class="message-img">\n' +
            '           <img class="logo" src="', avatar, '">',
            '        </div>\n' +
            '        <div class="message-info">\n' +
            '           <div class="message-name-date"><span>', nickname, '</span><span class="date">', time_str, '</span></div>\n' +
            '        <div class="message-message">', msg, '</div>\n' +
            '   </div>\n' +
            ' </div>'
        ].join("");
    },
    // 發送消息默認滾動條到底部
    scrollToBottom: function () {
        var total_height = $('.message')[0].scrollHeight;
        $('.message').scrollTop(total_height);
    },
    // socket的分配狀態
    // ws_connect代表链接过来了
    // hello 代表初次链接成功了.系统返回的返回消息.
    // assign_kf 这个是链接成功过后  系统分配了客服.
    // change_kf 代表是更换客服.
    // reply 代表客服回复消息过来了．
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
            case "hello":
                kf_ws_service.socketSend( that.buildMsg('guest_connect',{} ));
                break;
            case "assign_kf":
                that.data['t_id'] = data.data['sn'];
                that.data['t_name'] = data.data['name'];
                that.data['t_avatar'] = data.data['avatar'];
                //显示一些系统文字提醒，例如已分配哪个客服
                break;
            case "change_kf":
                that.data['t_id'] = data.data['sn'];
                that.data['t_name'] = data.data['name'];
                that.data['t_avatar'] = data.data['avatar'];
                break;
            case "reply":
                $('.message').append( that.buildCsMsg(that.data['t_name'], that.data['t_avatar'], data.data.content) );
                that.scrollToBottom();
                break;
            case 'close_guest':
                console.dir('您已经被客服关闭聊天了');
                // 主动调用.
                // kf_ws_service.ws.close();
                break;
        }
    }
}
$(document).ready(function () {
    chat_ops.init();
});
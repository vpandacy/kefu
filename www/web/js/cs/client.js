;


// * 游客进入页面连接服务：guest_in
// * 聊天窗口打开的时候：guest_connect
// * 聊天：chat
// * 游客关闭聊天：guest_close
// * 客服关闭聊天：kf_close_guest
// * 分配客服：assign_kf
// * 换客服：change_kf
// * 表单：form
// * 系统消息：system
var client = {
    socket: null,
    config: {
        host: '0.0.0.0',
        port: '8282'   // 这里是网关的端口
    },
    init: function () {
        var socket = new WebSocket('ws://0.0.0.0:8282');
        socket.addEventListener('open', this.open);
        socket.addEventListener('message', this.message);
        socket.addEventListener('close', this.close);
        socket.addEventListener('error', this.error);
        this.socket = socket;
    },
    open: function (event) {
        client.socket.send(client.buildMsg('guest_in',{
            msg: 'hello world'
        }))
    },
    message: function (event) {
        var data = event.data;

        if(event.type != 'message') {
            // 断开链接.
            client.socket.close();
        }

        data = JSON.parse(data);

        if(data.cmd == 'ping') {
            return client.socket.send(client.buildMsg('pong'));
        }

        // 开始解析事件了.

    },
    close: function () {
        
    },
    error: function () {
        
    },
    // 暂时先这样.其他先不管.
    buildMsg: function (cmd, data) {
        if(!data) {
            data = '';
        }
        return JSON.stringify({
            cmd: cmd,
            data: data
        });
    }
};

$(document).ready(function () {
    client.init();
});
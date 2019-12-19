// socket.主要封转socket的消息发送和链接.
(function(window){
    var config = JSON.parse( $(".hidden_wrapper input[name=params]").val() );


    // 主要聊天的功能.
    var Socket = function () {
        this.ws = null;
    };

    // 初始化socket连接. 将处理的消息事件给抛出去.
    Socket.prototype.init = function(chat) {
        this.ws = new WebSocket('ws://' + config.ws);
        //这个事件应该标注 状态 已连接（绿色）
        this.ws.addEventListener('open', function () {
            chat.handleMessage( { "cmd": "ws_connect" } );
        });

        // 接收websocket返回的信息.
        this.ws.addEventListener('message', function (event) {
            var data = JSON.parse(event.data);
            chat.handleMessage( data );
        });

        // 关闭websocket发送的信息.
        this.ws.addEventListener('close', function () {
            //关闭
        });

        // 这里是websocket发生错误的.信息.
        this.ws.addEventListener('error', function () {
            //错误要把信息发回到监控中心，并且是不是要重连几次，不行就关闭了
        });
    };

    // 发送消息.
    Socket.prototype.socketSend = function(msg) {
        this.ws.send(JSON.stringify(msg));
    };

    // 关闭链接.
    Socket.prototype.close = function() {
        this.ws.close();
    };

    /**
     * 生成发送消息.
     * @param cmd
     * @param data
     * @returns {{cmd: *, data: *}}
     */
    Socket.prototype.buildMsg = function (cmd, data) {
        // 获取当前的uuid.
        var current_uuid = $('.content-message-active').attr('data-uuid');

        data.f_id = config.sn;
        data.t_id = current_uuid;
        data.msn  = config.msn;

        return {
            cmd: cmd,
            data: data
        };
    };

    window.Socket = Socket;
})(window);
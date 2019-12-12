;
var socket = null;

var client = {
    init: function () {
        socket = this.initSocket();
    },
    initSocket: function () {
        // 使用socket来链接.
        var socket = new WebSocket('ws://0.0.0.0:8282');

        // 打开websocket信息.
        socket.addEventListener('open', function () {
            // 先定义一个不同的事件.后面在根据不同的定义不同的内容.
            socket.send(client.buildMsg('guest_in_cs', {
                cs_sn: $('[name=cs_sn]').val()
            }));
        });

        // 接收websocket返回的信息.
        socket.addEventListener('message', function (event) {
            console.dir(event);
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
            send_data.to = user.cs_sn ? user.cs_sn : '';
        }

        return JSON.stringify(send_data);
    }
};


$(document).ready(function () {
    client.init();
});
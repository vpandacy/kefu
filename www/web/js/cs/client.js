;
var socket = null,
    online_users = [], // 在线游客列表.
    current_uuid = '';  // 当前游客. 这里是需要处理信息的. 如果不是当前游客的窗口.

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
            var msg = $('.sumbit-input').html();

            if(!msg || msg.length < 0) {
                return false;
            }

            that.send(msg);
        });

        $('.sumbit').on('click', function () {
            var msg = $('.sumbit-input').html();

            if(!msg) {
                return false;
            }
            that.send(msg);
        });

        // 这里是删除鼠标右键的效果.
        $(document).on('click', function () {
            $('#menu').css({
                display: 'none'
            });
        });

        $('.online').on('contextmenu', '.tab-content-list', function (event) {
            // 阻止事件发生.
            event.preventDefault();

            var uuid = $(this).attr('data-uuid');

            that.showMenu(uuid, event);
        });


    },
    send: function(msg) {
        socket.send(client.buildMsg('chat', {
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
            '      <div class="message-message message-message-my">',msg,'</div>',
            '   </div>',
            '</div>'
        ].join(""));

        client.scrollToBottom();
    },
    initSocket: function () {
        var that = this,
        // 使用socket来链接.
            socket = new WebSocket('ws://192.168.117.122:8282');

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
                    nickname: data.data.nickname,
                    allocationTime: data.data.allocation_time
                };

                ChatSorage.setItem(user.customer, user);
                // 插入到第一个.然后在渲染到对应的视图中去.
                online_users.unshift(user.customer);

                that.renderOnlineList();
            }

            if(data.cmd == 'ping') {
                socket.send(client.buildMsg('pong'))
            }

            if(data.cmd == 'chat') {
                // 这里要组装数据.
                // 获取游客的信息.
                var uuid = data.data.f_id,
                    user = ChatSorage.getItem(uuid);

                $('.exe-content-history').append(client.buildCustomerMsg(user.nickname, user.avatar, data.data.content));

                client.scrollToBottom();
                // 重新将uuid给置顶.
                that.renderOnlineList(uuid);
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
    // 得到游客的信息.
    buildMsg: function (cmd, data) {
        var user = ChatSorage.getItem('user'),
            send_data = {};

        if(!user) {
            user = {};
        }

        send_data.cmd = cmd;

        send_data.data = {};
        if(data) {
            send_data.data = data;
        }

        send_data.data.f_id = $('[name=cs_sn]').val();
        send_data.data.t_id = user.customer ? user.customer : '';

        return JSON.stringify(send_data);
    },
    // 渲染游客的消息记录.
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
            '       <div class="message-message">',msg,'</div>',
            '   </div>',
            '</div>'
        ].join("");
    },
    // 滚动到页面最底部.
    scrollToBottom: function () {
        var height = $('.exe-content-history')[0].scrollHeight;

        $('.exe-content-history').scrollTop(height);
    },
    // 渲染在线列表.
    renderOnlineList: function (uuid) {
        // 这里没有列表.所以需要重新处理一下.
        if(uuid) {
            online_users.sort(function (id) {
                return id == uuid ? -1 : 0
            });
        }

        var html = online_users.map(function (uuid) {
            var user = ChatSorage.getItem(uuid);
            // 这里有图标展示. 这里要注意一下.
            var icon_types = ['shoji','diannao', 'baidu1'];
            return  [
                '<div oncontextmenu="tab.list(event)" class="tab-content-list" data-uuid="',uuid,'">',
                '   <div>',
                '       <i class="iconfont icon-shouji"></i>',
                '       <span>',user.nickname,'</span>',
                '   </div>',
                '   <div>',
                '       <span class="content-list-time">',user.allocationTime,'</span>',
                '   </div>',
                '</div>'
            ].join("");
        });

        $('.tab-content .online').html(html.join(''));
    },
    // 展示对应的右键菜单.
    showMenu:function (uuid, event) {
        var x = ((parseInt(event.clientX) + 100) <= window.innerWidth) ? event.clientX : event.clientX - 98,
            y = ((parseInt(event.clientY) + 140) <= window.innerHeight) ? event.clientY : event.clientY - 138;

        // 展示出来.并将uuid存成局部变量.
        $('#menu').css({
            left: x + 'px',
            top : y + 'px',
            display: 'block'
        });

        // 先取消并注册事件.
        $('#menu a').off('click').on('click', function () {
            var event = $(this).attr('data-event');

            switch (event) {
                case 'edit':
                    alert('您点击了编辑,uuid:' + uuid);
                    break;
                case 'del':
                    alert('您点击的删除,uuid:' + uuid);
                    break;
                case 'black':
                    alert('点击了拉入黑名单,uuid:' + uuid);
                    break;
            }
            return false;
        });
    }
};

// 这个是界面的主要动画效果.
var page = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        $('.icon-guanbi').on('click', function () {
            $('#chatExe .flex1').css({'visibility': 'hidden;'});
        });
    }
};


$(document).ready(function () {
    client.init();
    page.init();
});
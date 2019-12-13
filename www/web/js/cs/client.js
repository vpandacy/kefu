;
var socket = null,
    online_users = [], // 在线游客列表.
    current_uuid = '';  // 当前游客. 这里是需要处理信息的. 如果不是当前游客的窗口.
// 关于前台聊天的基本功能.
var kf_ws_service = {
    ws: null,
    connect:function( host ){
        this.ws = new WebSocket('ws://' + host);
        //这个事件应该标注 状态 已连接（绿色）
        this.ws.addEventListener('open', function () {
            client.handlerMessage( { "cmd":"ws_connect" } );
        });
        // 接收websocket返回的信息.
        this.ws.addEventListener('message', function (event) {
            var data = JSON.parse(event.data);
            client.handlerMessage( data );
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
        this.ws.send(JSON.stringify(msg));
    }
};

var client = {
    init: function () {
        this.eventBind();
        this.data = JSON.parse( $(".hidden_wrapper input[name=params]").val() );
        this.initSocket();
    },
    // 事件绑定信息.
    eventBind: function() {
        var that = this;
        // 输入框的回车事件.
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
        // 点击发送按钮.
        $('.sumbit').on('click', function () {
            var msg = $('.sumbit-input').html();

            if(!msg) {
                return false;
            }
            that.send(msg);
        });

        // 这里删除 鼠标右键的效果.
        $(document).on('click', function () {
            $('#menu').css({
                display: 'none'
            });
        });
        // 监听鼠标右键.
        $('.tab-content .online').on('contextmenu', '.tab-content-list', function (event) {
            // 阻止事件发生.
            event.preventDefault();

            if($(this).hasClass('content-no-message')) {
                return true;
            }

            var uuid = $(this).attr('data-uuid');

            page.showMenu(uuid, event);
        });

        // 选择聊天对象.
        $('.tab-content .online').on('click', '.tab-content-list', function () {
            var uuid = $(this).attr('data-uuid');
            if(!uuid) {
                return false;
            }

            var user = ChatStorage.getItem(uuid);
            if(!user) {
                return false;
            }

            user.new_message = 0;
            $(this).removeClass('content-new-message');
            $(this).find('.content-new-message').removeClass('content-new-message');
            $(this).addClass('content-message-active').siblings().removeClass('content-message-active');
            current_uuid = uuid;
            ChatStorage.setItem(uuid, user);

            // 要开始渲染聊天窗口了.
            page.renderChat(uuid);
            page.scrollToBottom();
        });
    },
    // 发送消息函数.
    send: function(msg) {
        var user = ChatStorage.getItem(current_uuid, []),
            time_str = page.getCurrentTimeStr();

        // 这里要给current_uuid的用户存储信息.不然到时候都不知道给谁了.
        socket.send(client.buildMsg('chat', {
            content: msg
        }));

        if(!user.messages) {
            user.messages = [];
        }

        // 消息信息.
        user.messages.push({
            f_id: $('[name=cs_sn]').val(),
            t_id: current_uuid,
            content: msg,
            time:time_str
        });

        ChatStorage.setItem(current_uuid, user);

        // 清空掉.然后在将这个展示到对应的消息上去.
        $('.sumbit-input').text('');

        $('.exe-content-history').append(page.renderCsMsg('我',msg, time_str));
        page.scrollToBottom();
    },
    // 初始化websocket
    initSocket: function () {
        kf_ws_service.connect( this.data['ws'] );
    },
    // 分配客服处理.
    assignKf:function(data) {
        var user = {
            customer: data.data.customer,
            avatar: data.data.avatar,
            nickname: data.data.nickname,
            allocationTime: data.data.allocation_time
        };

        var old_user = ChatStorage.getItem(user.customer, {});
        user = Object.assign({}, old_user,user);

        if(!user.messages) {
            user.messages = [];
        }

        // 如果当前会话的列表中有  就不用去渲染处理了.
        if(online_users.indexOf(user.customer) < 0) {
            ChatStorage.setItem(user.customer, user);
            // 插入到第一个.然后在渲染到对应的视图中去.
            online_users.unshift(user.customer);
        }

        page.renderOnlineList();
    },
    // 被动聊天处理.
    chat: function(data) {
        // 这里要组装数据.
        // 获取游客的信息.
        var uuid = data.data.f_id,
            user = ChatStorage.getItem(uuid),
            messages = user.messages,
            time_str = page.getCurrentTimeStr();

        if(uuid != current_uuid) {
            // 有新消息了.
            user.new_message = 1;
        }
        // 这里是判断消息长度
        if(messages.length >= 20) {
            messages.shift();
        }

        messages.push({
            f_id: uuid,
            t_id: data.data.t_id,
            content: data.data.content,
            time: data.data.time
        });

        user.messages = messages;

        if(uuid == current_uuid) {
            $('.exe-content-history').append(page.renderCustomerMsg(user.nickname, user.avatar, data.data.content, time_str));
        }

        page.scrollToBottom();
        ChatStorage.setItem(uuid, user);
        // 重新将uuid给置顶.
        page.renderOnlineList(uuid);
    },
    // 得到游客的信息.
    buildMsg: function (cmd, data) {
        data['f_id'] = this.data['sn'];
        data['msn'] = this.data['msn'];
        var params = {
            cmd:cmd,
            data:data
        };
        return params;
    },
    handlerMessage:function( data ) {
        var that = this;
        switch (data['cmd']) {
            case "ping":
                kf_ws_service.socketSend({"cmd": "pong"});
                break;
            case "ws_connect":
                kf_ws_service.socketSend(that.buildMsg('kf_in', {}));
                break;
            case "assign_guest"://分配客户过来，要在页面标注熟悉，不能用全局，因为有多个游客
                break;
            case "reply":
                $('.online-content').append(that.buildCsMsg(that.data['t_name'], that.data['t_avatar'], data.data.content));
                that.scrollToBottom();
                break;
        }
    }
};

// 这个是界面的主要动画效果.
var page = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        $('.icon-guanbi').on('click', function () {
            $('#chatExe .flex1').css({'display': 'none'});

            $('.content-message-active').removeClass('content-message-active');
        });
    },
    // 渲染聊天窗口
    renderChat: function (uuid) {
        var user = ChatStorage.getItem(uuid),
            that = this;

        $('#chatExe .flex1 .exe-header-info-left>span:first-child').text(user.nickname);
        // 只保存最新的20条,超过了就不保存了.因为localStorage空间有限制.到时在处理成其他的.
        if(!user.messages || user.messages.length < 1) {
            // 置空.就是没有聊天记录.
            $('.flex1 .exe-content-history').html('');
            $('#chatExe .flex1').css({'display': 'flex'});
            this.eventBind();
            return false;
        }

        // 开始处理剩下的. 循环去处理就可以了. 要定义对应的信息.
        var html = user.messages.map(function (message) {
            if(message.f_id == uuid){
                return that.renderCustomerMsg(user.nickname, user.avatar, message.content, message.time);
            }

            return that.renderCsMsg('我',  message.content, message.time);
        });

        $('.flex1 .exe-content-history').html(html.join(''));
        $('#chatExe .flex1').css({'display': 'flex'});
        this.eventBind();
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
    },
    // 滚动到页面最底部.
    scrollToBottom: function () {
        var height = $('.exe-content-history')[0].scrollHeight;

        $('.exe-content-history').scrollTop(height);
    },
    // 渲染在线列表.
    renderOnlineList: function (source_uuid) {
        $('.keep-census .online').text(online_users.length);

        if(online_users.length < 1) {
            $('.tab-content .online').html('<div class="tab-content-list content-no-message">暂无消息</div>');
            return false;
        }

        // 这里没有列表.所以需要重新处理一下.
        if(source_uuid) {
            online_users.sort(function (id) {
                return id == source_uuid ? -1 : 0
            });
        }

        var html = online_users.map(function (uuid) {
            var user = ChatStorage.getItem(uuid),
                class_name = user.new_message >　0 ? 'content-new-message ' : '';

            if(uuid == current_uuid) {
                class_name = class_name + 'content-message-active';
            }

            // 这里有图标展示. 这里要注意一下.
            var icon_types = ['shoji','diannao', 'baidu1'];
            return  [
                '<div class="tab-content-list ', class_name, '" data-uuid="',uuid,'">',
                '   <div class="', user.new_message <= 0 ? '' : 'content-new-message','">',
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
    // 渲染游客的消息记录.
    renderCustomerMsg: function (nickname, avatar, msg, time_str) {
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
    renderCsMsg: function (nickname, msg, time_str) {
        return [
            '<div class="content-message message-my">',
            '   <div class="message-info">',
            '      <div class="message-name-date name-date-my">',
            '          <span class="date">', time_str, '</span>',
            '          <span class="message-name">', nickname, '</span>',
            '      </div>',
            '      <div class="message-message message-message-my">', msg, '</div>',
            '   </div>',
            '</div>'
        ].join("");
    },
    // 获取当前时间.
    getCurrentTimeStr: function () {
        var date = new Date();

        return [
            date.getHours(),
            date.getMinutes(),
            date.getSeconds()
        ].map(function (value) {
            return value < 10 ? '0' + value : value;
        }).join(':');
    }
};


$(document).ready(function () {
    client.init();
    page.init();

    // 菜单栏切换.
    $(".tab .tab-switch .tab-one").click(function() {
        // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
        $(this).addClass("switch-action").siblings().removeClass("switch-action");
        // parent 父元素 next 下一个兄弟节点  children 子节点
        $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
    });
});
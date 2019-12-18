;
var online_users = [], // 在线游客列表.
    context_menu = new Contextmenu('#menu', '.tab-content .online');

var client = {
    init: function () {
        this.eventBind();
        this.initSocket();
    },
    // 初始化websocket
    initSocket:function(){
        this.config = JSON.parse( $(".hidden_wrapper input[name=params]").val() );

        this.ws = new WebSocket('ws://' + this.config.ws);
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
    // 发送消息.
    socketSend: function (msg) {
        this.ws.send(JSON.stringify(msg));
    },
    // 事件绑定信息.
    eventBind: function() {
        var that = this;
        // 输入框的回车事件.
        $('.sumbit-input').on('keydown', function (event) {
            var current_uuid = $('.content-message-active').attr('data-uuid');
            if(event.keyCode == 13 && event.shiftKey || event.keyCode != 13) {
                return true;
            }

            event.preventDefault();

            // 这里准备提交
            var msg = $('.sumbit-input').html();

            if(!msg || msg.length < 0) {
                return false;
            }

            if(!current_uuid) {
                return $.msg('请选择游客进行聊天');
            }

            that.send(current_uuid,msg);
        });
        // 点击发送按钮.
        $('.sumbit').on('click', function () {
            var msg = $('.sumbit-input').html(),
                current_uuid = $('.content-message-active').attr('data-uuid')

            if(!msg) {
                return false;
            }


            if(!current_uuid) {
                return $.msg('请选择游客进行聊天');
            }

            that.send(current_uuid, msg);
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
            ChatStorage.setItem(uuid, user);

            // 要开始渲染聊天窗口了.
            page.renderChat(uuid);
            page.scrollToBottom();
        });

        // 点击选择常用语
        $('.content-one .content-select').on('click', function () {
            var content = $(this).find('span').html();

            if(content.length <= 0) {
                return false;
            }

            $('.sumbit-input').html(content);
        });
    },
    // 发送消息函数.
    send: function(current_uuid, msg) {
        var user = ChatStorage.getItem(current_uuid, []),
            time_str = page.getCurrentTimeStr();

        // 这里要给current_uuid的用户存储信息.不然到时候都不知道给谁了.
        this.socketSend(client.buildMsg('reply', {
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

        user.messages = user.messages.slice(0,20);
        ChatStorage.setItem(current_uuid, user);

        // 清空掉.然后在将这个展示到对应的消息上去.
        $('.sumbit-input').text('');

        $('.exe-content-history').append(page.renderCsMsg('我',msg, time_str));
        page.scrollToBottom();
    },
    // 分配客服处理.
    assignKf:function(data) {
        var user = {
            uuid: data.data.f_id,
            avatar: data.data.avatar,
            nickname: data.data.nickname,
            allocationTime: data.data.allocation_time
        };

        var old_user = ChatStorage.getItem(user.uuid, {});
        user = Object.assign({}, old_user,user);

        if(!user.messages) {
            user.messages = [];
        }

        if(online_users.length == 1) {
            user.messages = 0;
        }

        ChatStorage.setItem(user.uuid, user);

        // 如果当前会话的列表中有  就不用去渲染处理了.
        if(online_users.indexOf(user.uuid) < 0) {
            // 插入到第一个.然后在渲染到对应的视图中去.
            online_users.unshift(user.uuid);
        }

        page.renderOnlineList();
        if(online_users.length == 1) {
            $('.online [data-uuid="' +online_users[0]+  '"]').addClass('content-message-active');
            page.renderChat(user.uuid);
            page.scrollToBottom();
        }
    },
    // 被动聊天处理.
    chat: function(data) {
        var current_uuid = $('.content-message-active').attr('data-uuid');
        // 获取游客的信息.
        var uuid = data.data.f_id,
            user = ChatStorage.getItem(uuid),
            messages = user.messages ? user.messages : [],
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
        var current_uuid = $('.content-message-active').attr('data-uuid');

        data.f_id = this.config.sn;
        data.t_id = current_uuid;
        data.msn  = this.config.msn;
        return {
            cmd:cmd,
            data:data
        };
    },
    // 处理消息.
    handlerMessage:function( data ) {
        switch (data.cmd) {
            case "ping":
                this.socketSend({"cmd": "pong"});
                break;
            case "ws_connect":
                this.socketSend(this.buildMsg('kf_in', {}));
                break;
            case "guest_close":
                console.dir(data);
                break;
            case "guest_connect":
                this.assignKf(data);
                break;
            case "chat":
                this.chat(data);
                break;
        }
    }
};

// 这个是界面的主要动画效果.
var page = {
    init: function () {
        this.eventBind();
        this.emojInit();
    },
    eventBind: function () {
        $('.icon-guanbi').on('click', function () {
            $('#chatExe .flex1').css({'display': 'none'});

            $('.content-message-active').removeClass('content-message-active');
        });
    },
    // 初始化表情
    emojInit: function () {
        sdEditorEmoj.Init(emojiconfig);
        sdEditorEmoj.setEmoji({
            type: 'div',
            id: "content"
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
    // 滚动到页面最底部.
    scrollToBottom: function () {
        var height = $('.exe-content-history')[0].scrollHeight;

        $('.exe-content-history').scrollTop(height);
    },
    // 渲染在线列表.
    renderOnlineList: function (source_uuid) {
        var current_uuid = $('.content-message-active').attr('data-uuid');
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
            var user = ChatStorage.getItem(uuid);
            var class_name = user.new_message >　0 ? 'content-new-message ' : '';

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
    context_menu.init();

    // 菜单栏切换.
    $(".tab .tab-switch .tab-one").click(function() {
        // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
        $(this).addClass("switch-action").siblings().removeClass("switch-action");
        // parent 父元素 next 下一个兄弟节点  children 子节点
        $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
    });
});
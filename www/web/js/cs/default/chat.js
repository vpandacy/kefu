// 这里是实现所有关于聊天的逻辑.
(function(window){
    var Chat = function () {
        // ws.
        this.socket = new Socket();
        // 页面.
        this.page = new Page();
        // 右键菜单
        this.contextmenu = new Contextmenu('#menu', '.tab-content .online', this.page);
    };

    // 初始化Chat.
    Chat.prototype.init = function() {
        // 初始化ws socket.
        this.socket.init(this);
        // 右键菜单初始化.
        this.contextmenu.init();
        // 界面初始化　
        this.page.init();
        // 初始化聊天事件.
        this.eventBind();
        //客服下线操作
        this.offOnline();
        //客服退出操作
        this.signOut();
    };

    // 事件绑定.
    Chat.prototype.eventBind = function() {
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

            that.handleSend(current_uuid,msg);
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

            that.handleSend(current_uuid, msg);
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
            that.page.renderChat(uuid);
            that.page.scrollToBottom();
        });


    };

    // 绑定发送事件.
    Chat.prototype.handleSend = function(current_uuid, msg) {
        var user = ChatStorage.getItem(current_uuid, []),
            time_str = this.page.getCurrentTimeStr();

        if(!user.is_online) {
            return $.msg('该游客已经下线了');
        }

        // 这里要给current_uuid的用户存储信息.不然到时候都不知道给谁了.
        this.socket.socketSend(this.socket.buildMsg('reply', {
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

        $('.exe-content-history').append(this.page.renderCsMsg('我',msg, time_str));
        this.page.scrollToBottom();
    };

    // 绑定socket的消息事件. 注意.这里肯定有问题. 这里的this会改变为Socket本身.
    Chat.prototype.handleMessage = function(data) {
        switch (data.cmd) {
            case "ping":
                this.socket.socketSend({"cmd": "pong"});
                break;
            case "ws_connect":
                this.socket.socketSend(this.socket.buildMsg('kf_in', {}));
                break;
            case "guest_close":
                // 先获取用户的信息
                this.guestClose(data.data);
                // 刷新界面.
                this.page.renderOnlineList();
                break;
            case "guest_connect":
                this.assignKf(data);
                break;
            case "chat":
                this.chat(data);
                break;
            // 这里要存入一份.然后在进行渲染. 这里是等待聊天的数据.
            case 'guest_connect_wait':

                break;
        }
    };

    // 这里是游客登录进来了.已经分配给该客户的动作.
    Chat.prototype.assignKf = function(data) {
        var user = {
            uuid: data.data.f_id,                       // 用户ID
            avatar: data.data.avatar,                   // 用户头像
            nickname: data.data.nickname,               // 用户昵称
            allocationTime: data.data.allocation_time,  // 用户的发起时间
            is_online: 1                                // 是否在线,1在线.0下线.
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

        this.page.renderOnlineList();
        if(online_users.length == 1) {
            $('.online [data-uuid="' +online_users[0]+  '"]').addClass('content-message-active');
            this.page.renderChat(user.uuid);
            this.page.scrollToBottom();
        }
    };

    // 客服消息处理.
    Chat.prototype.chat = function(data) {
        var current_uuid = $('.content-message-active').attr('data-uuid');
        // 获取游客的信息.
        var uuid = data.data.f_id,
            user = ChatStorage.getItem(uuid),
            messages = user.messages ? user.messages : [],
            time_str = this.page.getCurrentTimeStr();

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
            $('.exe-content-history').append(this.page.renderCustomerMsg(user.nickname, user.avatar, data.data.content, time_str));
        }

        this.page.scrollToBottom();
        ChatStorage.setItem(uuid, user);
        // 重新将uuid给置顶.
        this.page.renderOnlineList(uuid);
    };

    // 客服下线操作
    // 信息框-例2
    //
    // layer.msg('你确定你很帅么？', {
    //   time: 0 //不自动关闭
    //   ,btn: ['必须啊', '丑到爆']
    //   ,yes: function(index){
    //     layer.close(index);
    //     layer.msg('雅蠛蝶 O.o', {
    //       icon: 6
    //       ,btn: ['嗷','嗷','嗷']
    //     });
    //   }
    // });
    Chat.prototype.offOnline = function () {
        $('.exe-off-online').click(function () {
            $.post('/cs/user/offline',{},function () {
               $('.icon-zaixian').replaceWith('<i class="iconfont icon-lixian icon icon-action fsize32" title="离线"></i>');
            });
        });
    };
    Chat.prototype.signOut = function () {
      $('.icon-tuichu').click(function () {
            if(online_users.length != 0){
                $.msg('请先将游客对话处理完成!');
                return;
            }
      });
    };
    /**
     * 游客主动关闭.
     * @param data
     * @returns {boolean}
     */
    Chat.prototype.guestClose = function(data) {
        var user = ChatStorage.getItem(data.uuid);

        if(!user) {
            return false;
        }

        user.is_online = 0;
        // 存储进去.
        ChatStorage.setItem(data.uuid, user);
    };

    window.Chat = Chat;
})(window);

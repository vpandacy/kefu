// 这里是实现所有关于聊天的逻辑.
(function(window){
    var  historyDate = [];
    var Chat = function () {
        // ws.
        this.socket = new Socket();
        // 页面.
        this.page = new Page();
        // 右键菜单
        this.contextmenu = new Contextmenu('#menu', '.tab-content .online', this.page);
        //音效动画句柄
        this.audio = document.getElementById("tip_music");
    };

    //监听事件
    // audio.addEventListener("canplaythrough", function () {
    //     //alert('音频文件已经准备好，随时待命');
    // }, false);

    // 初始化Chat.
    Chat.prototype.init = function() {
        // 初始化游客.
        this.initUser();
        // 初始化ws socket.
        this.socket.init(this);
        // 右键菜单初始化.
        this.contextmenu.init();
        // 界面初始化.
        this.page.init();
        // 初始化聊天事件.
        this.eventBind();
        //客服下线操作.
        this.bindOnlineEvent();
        //客服退出操作.
        this.signOut();
        // 显示历史记录.
        this.lookHistory();
    };

    // 初始化所有的用户.
    Chat.prototype.initUser = function() {
        // 默认清除掉所有的信息.
        // ChatStorage.clearAll();
        var uuids = [];

        for(var i in all_users) {
            uuids.push(all_users[i].uuid);
            if(ChatStorage.getItem(all_users[i].uuid)) {
                continue;
            }
            // 设置信息即可.
            ChatStorage.setItem(all_users[i].uuid, all_users[i]);
        }

        for(var key in localStorage) {
            if(uuids.indexOf(localStorage[key]) <= -1) {
                // 就直接来删除对应的列了.
                ChatStorage.removeItem(localStorage[key]);
            }
        }
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
            historyDate = []
            if($(this).attr('data-uuid') != $('.content-message-active').attr('data-uuid')){
                $('.exe-content-history-ready').find('.content-message').remove();
                $('.exe-content-history-content-null').html('');
                $('.exe-content-history').find('.exe-content-history-title').html('');
                $('.history-look').attr('style','pointer-events: all;');
            }
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

        // 选择姓名.
        $('.flex1 .name').on('click', function (e) {
            var current_uuid = $('.flex1').attr('data-uuid'),
                elem = $(this);

            e.preventDefault();
            that.saveInformation('name', current_uuid, elem);
        });

        // 填写手机号.
        $('.flex1 .mobile').on('click', function (e) {
            var current_uuid = $('.flex1').attr('data-uuid'),
                elem = $(this);

            e.preventDefault();
            that.saveInformation('mobile', current_uuid, elem);
        });

        // 填写邮箱.
        $('.flex1 .email').on('click', function (e) {
            var current_uuid = $('.flex1').attr('data-uuid'),
                elem = $(this);

            e.preventDefault();

            that.saveInformation('email', current_uuid,elem);
        });

        // 填写ＱＱ.
        $('.flex1 .qq').on('click', function (e) {
            var current_uuid = $('.flex1').attr('data-uuid'),
                elem = $(this);

            e.preventDefault();
            that.saveInformation('qq', current_uuid,elem);
        });

        // 填写wechat.
        $('.flex1 .wechat').on('click', function (e) {
            var current_uuid = $('.flex1').attr('data-uuid'),
                elem = $(this);

            e.preventDefault();

            that.saveInformation('wechat', current_uuid, elem);
        });

        // 填写desc.
        $('.flex1 .desc').on('click', function (e) {
            var current_uuid = $('.flex1').attr('data-uuid'),
                elem = $(this);

            e.preventDefault();
            e.stopPropagation();

            that.saveInformation('desc', current_uuid,elem);
        });
    };

    // 保存用户信息.
    Chat.prototype.saveInformation = function(name, current_uuid, elem) {
        var text = elem.text(),
            val = text == '暂无' ? '' : text,
            type = name == 'desc' ? 'textarea' : 'input',
            data = {};

        // 防止重复点击.
        if(elem.find('input').length > 0) {
            return false;
        }

        // 防止重复填写.
        if(elem.find('textarea').length > 0) {
            return false;
        }

        if(name != 'desc') {
            elem.html('<input name="'+ name + '" value="'+ val +'" placeholder="请输入">');
            elem.find('input').focus();
        }else{
            elem.attr('title',val);
            elem.html('<textarea name="'+name+'">'+val+'</textarea>');
            elem.find('textarea').focus();
        }

        elem.find(type).on('blur', function () {
            var value = $(this).val();
            if(!value) {
                return elem.text(text);
            }

            data[name] = value;
            data.uuid  = current_uuid;

            $.ajax({
                type: 'POST',
                data: data,
                dataType: 'json',
                url: cs_common_ops.buildKFCSurl('/visitor/save'),
                success: function (res) {
                    if(res.code == -302) {
                        return $.msg(res.msg, false, function(){
                            location.href = res.data.url;
                        });
                    }

                    if(res.code != 200) {
                        return $.msg(res.msg);
                    }

                    elem.text(value);
                }
            })
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

        $('.exe-content-history .exe-content-history-content').append(this.page.renderCsMsg('我',msg, time_str));
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
                this.guestConnectWait(data);
                break;
            case 'kf_logout':
                // 这里弹出信息.
                this.logout();
                break;
        }
    };

    // 等待链接.
    Chat.prototype.guestConnectWait = function(data) {
        var user = {
            uuid: data.data.f_id,                       // 用户ID
            avatar: data.data.avatar,                   // 用户头像
            nickname: data.data.nickname,               // 用户昵称
            allocationTime: data.data.allocation_time,  // 用户的发起时间
            source: data.data.source,                   // 终端
            media: data.data.media || 0,                // 媒体
            is_online: 1                                // 是否在线,1在线.0下线.
        };

        var old_user = ChatStorage.getItem(user.uuid, {});
        user = Object.assign({}, old_user,user);

        user.messages = 0;

        if(offline_users.indexOf(user.uuid) <= -1) {
            offline_users.push(user.uuid);
            ChatStorage.setItem(user.uuid, user);
        }
        this.page.renderOfflineList();
    };

    // 这里是游客登录进来了.已经分配给该客户的动作.
    Chat.prototype.assignKf = function(data) {
        var user = {
            uuid: data.data.f_id,                       // 用户ID
            avatar: data.data.avatar,                   // 用户头像
            nickname: data.data.nickname,               // 用户昵称
            allocationTime: data.data.allocation_time,  // 用户的发起时间
            source: data.data.source,                   // 终端
            media: data.data.media || 0,                // 媒体
            is_online: 1                                // 是否在线,1在线.0下线.
        };

        var old_user = ChatStorage.getItem(user.uuid, {});
        user = Object.assign({}, old_user, user);

        if(!user.messages) {
            user.messages = [];
        }

        if(online_users.length == 1) {
            user.messages = [];
        }

        ChatStorage.setItem(user.uuid, user);

        // 如果在等待区中.
        if(offline_users.indexOf(user.uuid) >= 0) {
            offline_users = offline_users.filter(function (uuid) {
                return uuid != user.uuid;
            });

            this.page.renderOfflineList();
        }

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
            messages = user && user.messages ? user.messages : [],
            time_str = this.page.getCurrentTimeStr();

        if(uuid != current_uuid) {
            // 有新消息了.
            user.new_message = 1;
            try{
                this.audio.play();
            }catch (e) {

            }
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
            $('.exe-content-history .exe-content-history-content').append(this.page.renderCustomerMsg(user.nickname, user.avatar, data.data.content, time_str));
        }

        this.page.scrollToBottom();
        ChatStorage.setItem(uuid, user);
        // 重新将uuid给置顶.
        this.page.renderOnlineList(uuid);
    };
    Chat.prototype.bindOnlineEvent = function () {
        
        $('.exe-off-online').on('click', function () {
            var class_name = $(this).hasClass('icon-lixian')
                ? 'icon-zaixian'
                : 'icon-lixian';

            var url = class_name == 'icon-zaixian' ? '/user/do-online' : '/user/offline',
                elem = $(this);
                elemFlag = $('.menu-online');

            $.post(cs_common_ops.buildKFCSurl(url),{} ,function () {
                if(class_name == 'icon-zaixian') {
                    elem.removeClass('icon-lixian').addClass('icon-zaixian');
                    elem.attr('title','在线');
                    elemFlag.attr('style','background:rgb(86, 216, 59)');
                }else{
                    elem.removeClass('icon-zaixian').addClass('icon-lixian');
                    elem.attr('title','离线');
                    elemFlag.attr('style','background:rgb(216, 69, 59)');
                }
            });
        });
    };

    // 退出.
    Chat.prototype.signOut = function () {
        $('.icon-tuichu').click(function () {
            if(online_users.length != 0){
                $.msg('请先将游客对话处理完成!');
                return;
            }
            // 退出.
            location.href = cs_common_ops.buildKFCSurl('/user/logout');
        });
    };

    // 时间格式化
    Chat.prototype.getNowFormatDate = function (){
        var date = new Date();
        var seperator1 = "-";
        var seperator2 = ":";
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
            + " " + date.getHours() + seperator2 + date.getMinutes()
            + seperator2 + date.getSeconds();
        return currentdate;
    };

    // 查看历史消息
    Chat.prototype.lookHistory = function () {
        let date = this.getNowFormatDate();
        $('.history-look').click(function () {
            let formatDate =historyDate.length != 0 ? historyDate[0].created_time : date;
            let uuid = $('.content-message-active').attr('data-uuid');
            let name = $('.content-message-active').attr('data-name');
            $.post('/cs/visitor/message',{last_time:formatDate,uuid:uuid},function (res) {
                historyDate = res.data;
                /** 当无历史消息时禁止点击 **/
                historyDate.length == 0 ? $('.history-look').attr('style','pointer-events: none;') : '';
               let historyHtml = '';
                for (let i=0; i<historyDate.length; i++) {
                    if(historyDate[i].from_id == uuid){
                        historyHtml += '<div class="content-message">\n' +
                            '<div class="message-img">\n' +
                            '   <img class="logo" src='+ historyDate[i].cs_avatar +'>\n' +
                            '</div>\n' +
                            '<div class="message-info">\n' +
                            '   <div class="message-name-date"><span>'+ name + '</span><span class="date">'+historyDate[i].created_time + '</span></div>\n' +
                            '   <div class="message-message">'+ historyDate[i].content +'</div>\n' +
                            '  </div>\n' +
                            '</div>\t';
                    }else  {
                        historyHtml +=  '<div class="content-message message-my">\n' +
                            '   <div class="message-info">\n' +
                            '      <div class="message-name-date name-date-my">\n' +
                            '          <span class="date">'+ historyDate[i].created_time + '</span>\n' +
                            '          <span class="message-name">我</span>\n' +
                            '      </div>\n' +
                            '      <div class="message-message message-message-my">'+ historyDate[i].content +'</div>\n' +
                            '   </div>\n' +
                            '</div>';
                    }
                }
                $('.exe-content-history-ready').prepend(historyHtml);
                if(historyDate.length === 0){
                    $('.exe-content-history-content-null').html('' +
                        '<fieldset>\n' +
                        '  <legend>暂无消息</legend>\n' +
                        '</fieldset>');
                    $('.icon-jiazaizhong').hide();
                }else {
                    $('.exe-content-history-title').html('' +
                        '<fieldset>\n' +
                        '  <legend>以上是历史消息</legend>\n' +
                        '</fieldset>');
                    $('.exe-content-history-content-null').html('')
                    $('.icon-jiazaizhong').hide();
                }
            });
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

        // 如果在等待区.就直接删除.
        if(offline_users.indexOf(data.uuid) >= 0) {
            ChatStorage.removeItem(data.uuid);
            this.page.renderOfflineList();
        }else{
            // user.is_online = 0;
            // 存储进去.
            // ChatStorage.setItem(data.uuid, user);
            var current_uuid = $('.content-message-active').attr('data-uuid');

            if(data.uuid == current_uuid) {
                $('#chatExe .flex1').css({'display': 'none'});
                $('.content-message-active').removeClass('content-message-active');
            }

            online_users = online_users.filter(function (elem,curr) {
                return elem != data.uuid;
            });
            
            ChatStorage.removeItem(data.uuid);
            this.page.renderOnlineList();
        }
    };

    /**
     * 多个客服登录.强制退出.
     */
    Chat.prototype.logout = function() {
        return $.msg('您已经在其他地方登录了，如果继续操作请重新登录',false, function(){
            location.href = cs_common_ops.buildKFCSurl('/user/logout');
        });
    };

    window.Chat = Chat;
})(window);

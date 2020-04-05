;// 所有聊天框.
(function(window){
    var config = JSON.parse( $(".hidden_wrapper input[name=params]").val() ),
        repeatInterval = null,
        repeatMessages = [],
        closed = false,
        interval = null;

    // 这里还要在细分.
    // 这里要初始化第一次的时间. 用来查询历史记录.
    config.last_time = getCurrentDateTime(true);
    config.last_day  = config.last_time.split(" ")[0];

    if(!config) {
        console.log('未找到初始化配置信息.');
        return false;
    }
    // 获取当前时间 或者包含年月日的当前时间
    function getCurrentDateTime(is_all) {
        var date = new Date();

        var times = [
            date.getHours(),
            date.getMinutes(),
            date.getSeconds()
        ].map(function (value) {
            return value < 10 ? '0' + value : value;
        }).join(':');

        if(!is_all) {
            return times;
        }

        var full_date = [
            date.getFullYear(),
            parseInt(date.getMonth()) + 1,
            date.getDate()
        ].map(function (value) {
            return value < 10 ? '0' + value : value;
        }).join('-');

        return full_date + ' ' + times;
    }

    var socket = function (base_config) {
        // 注意.这里的所有都是以ID作为选择器来使用的
        // 获取原始输入框对象.
        this.input = base_config.hasOwnProperty('input')
            ? base_config.input
            : '#content';

        this.emoji = base_config.hasOwnProperty('emoji')
            ? base_config.emoji
            : 'content';
        // 获取原始的发送按钮.
        this.submit = base_config.hasOwnProperty('submit')
            ? base_config.submit
            : '.submit-button';

        // 这里是自定义的事件.
        this.handle = base_config.hasOwnProperty('handle')
            ? base_config.handle
            : function () {};

        // 这里是需要输出的信息框.
        this.output = base_config.hasOwnProperty('output')
            ? base_config.output
            : '.message';

        // 自定义渲染.
        this._renderCsMsg = base_config.hasOwnProperty('renderCsMsg')
            ? base_config.renderCsMsg
            : null;

        // 自定义消息.
        this._renderMsg = base_config.hasOwnProperty('renderMsg')
            ? base_config.renderMsg
            : null;

        // 定义系统消息的展示区域.
        this._renderSystemMessage = base_config.hasOwnProperty('renderSystemMessage')
            ? base_config.renderSystemMessage
            : null;

        // 隐藏聊天输入框.并展示其他信息.
        this._renderCloseChat = base_config.hasOwnProperty('renderCloseChat')
            ? base_config.renderCloseChat
            : null;

        this._showChat = base_config.hasOwnProperty('showChat')
            ? base_config.showChat
            : null;

        this._renderNickName = base_config.hasOwnProperty('renderNickName')
            ? base_config.renderNickName
            : null;

        // 保存socket信息.
        this.ws = null;
        // 表情初始化
        sdEditorEmoj.Init(emojiconfig);
        sdEditorEmoj.setEmoji({type:'div',id: this.emoji});
    };

    // 初始化整个的页面聊天.
    socket.prototype.init = function(params) {
        this.initSocket();
        // 绑定界面事件.
        this.eventBind();

        if(params) {
            this.params = params;
        }
    };

    // 初始化ws.
    socket.prototype.initSocket = function() {
        var that = this;
        this.ws = new WebSocket('ws://' + config['ws']);

        // ws打开事件.
        this.ws.addEventListener('open', function () {
            that.handleMessage({ "cmd" : "ws_connect" } );
        });

        // ws的回复事件.
        this.ws.addEventListener('message', function (event) {
            var data = JSON.parse(event.data);
            that.handleMessage( data );
        });

        // ws关闭事件.
        this.ws.addEventListener('close', function () {
        });

        // 这里是websocket发生错误的.信息.
        this.ws.addEventListener('error', function () {
            //错误要把信息发回到监控中心，并且是不是要重连几次，不行就关闭了
        });
    };

    // 关闭ws链接.
    socket.prototype.close = function() {
        this.ws.close();
        closed = true;
    };

    // 绑定界面的消息信息.
    socket.prototype.eventBind = function() {
        var that = this;
        // 鍵盤事件.
        $(this.input).on('keydown', function (event) {
            // 不等于回车的时候.
            if(event.keyCode == 13 && event.shiftKey || event.keyCode != 13) {
                return true;
            }
            // 修改掉其他事件
            event.preventDefault();
            that.send();
            return false;
        });

        // 发送按钮事件.
        $(this.submit).on('click', function (event) {
            event.preventDefault();
            that.send();
        });

        /**
         * 定时加载图标显示隐藏
         */
        $('.show-message .line').click(function () {
            var elem = $(this);
            $('.icon-jiazaizhong').show();
            elem.css({display: 'none'});
            $.ajax({
                type: 'POST',
                data: {
                    uuid: config.uuid,
                    last_time: config.last_time,
                    code: config.code
                },
                url: '/' + config.msn + '/visitor/history',
                dataType: 'json',
                success:function (res) {
                    $('.icon-jiazaizhong').hide();
                    elem.css({display: 'inline-block'});
                    if(res.code != 200) {
                        return false;
                    }

                    if(res.data.length <= 0) {
                        return false;
                    }

                    // 这里就开始渲染了.
                    $('.show-message').parents('.tip-div').after(that.renderHistory(res.data));
                },
                error: function () {
                    $('.icon-jiazaizhong').hide();
                    elem.css({display: 'inline-block'});
                }
            })
        });
    };

    // 自动断开聊天信息.
    socket.prototype.autoClose = function() {
        var that = this;
        if(interval) {
            clearInterval(interval);
        }

        if(config.auto_disconnect <= 0) {
            return false;
        }

        var auto_disconnect = parseInt(config.auto_disconnect);
        // auto_disconnect = 1000;
        interval = setInterval(function () {
            auto_disconnect -= 1;
            if(auto_disconnect <= 0) {
                clearInterval(interval);
                // 主动关闭聊天.
                that.ws.close();
                $(that.output).append(that.renderSystemMessage('由于您长时间没有对话，系统已经关闭您的会话'));
                // 这里要触发自定义渲染
                that.renderCloseChat();
            }
        }, 1000);
    };

    // 自动渲染关闭图形界面.
    socket.prototype.renderCloseChat = function() {
        if(this._renderCloseChat) {
            return this._renderCloseChat();
        }

        // 这里是默认的信息.
        return $('.chat-close').show();
    };

    // 页面发送消息．
    socket.prototype.send = function() {
        var msg = $(this.input).html();
        if(msg.length <= 0) {
            return false;
        }

        if(config.cs && config.cs.wait_num >= 1) {
            return false;
        }

        // 发送动作为chat.
        this.socketSend( this.buildMsg('chat',{
            'content': msg
        }));

        var div = this.renderMsg(msg, getCurrentDateTime());

        $(this.output).append(div);
        $(this.input).html('');
        this.scrollToBottom();
        this.autoClose();
        this.bindRepeatEvent();
    };

    // ws发送消息.
    socket.prototype.socketSend = function(msg){
        this.ws.send(JSON.stringify(msg));
    };

    // 组装消息信息.
    socket.prototype.buildMsg = function (cmd, data) {
        data.f_id = config.uuid;
        data.msn = config.msn;
        data.code = config.code;

        if( config.hasOwnProperty("cs") ){
            data.t_id = config.cs.t_id;
        }

        return {
            cmd:cmd,
            data:data
        };
    };

    // 将聊天框滚动到页面下面.
    socket.prototype.scrollToBottom = function(){
        var ele = $(this.output);
        ele.scrollTop(ele[0].scrollHeight);
    };

    // 渲染游客的聊天信息.
    socket.prototype.renderMsg = function (msg, time) {
        var div = '';
        if(this._renderMsg) {
            div = this._renderMsg(msg, time);
        }else{
            // 这里是默认的数据信息.
            div  = [
                '<div style="text-align: right">',
                '<div class="content-message online-my-message">\n' +
                '     <div class="message-info">\n' +
                '     <div class="message-name-date"><span>我</span><span class="date">',time,'</span></div>\n' +
                '   <div class="message-message">',msg,'</div>\n' +
                '  </div>\n' +
                '</div>',
                '</div>'
            ].join("")
        }

        return div;
    };

    // 渲染客服的聊天数据.
    socket.prototype.renderCsMsg = function(nickname, avatar, msg, time) {
        var div = ''

        if(this._renderCsMsg) {
            div = this._renderCsMsg(nickname, avatar, msg, time);
        }else{
            div = [
                '<div class="content-message">\n' +
                '        <div class="message-img">\n' +
                '           <img class="logo" src="', avatar, '">',
                '        </div>\n' +
                '        <div class="message-info">\n' +
                '           <div class="message-name-date"><span>', nickname, '</span><span class="date">', time, '</span></div>\n' +
                '        <div class="message-message">', msg, '</div>\n' +
                '   </div>\n' +
                ' </div>'
            ].join("")
        }

        return div;
    };

    // 处理消息回复.
    socket.prototype.handleMessage = function(data) {
        var that = this;
        switch (data.cmd) {
            case "ping":
                this.socketSend({ "cmd":"pong" });
                break;
            case "ws_connect":
                var params = {
                    ua: navigator.userAgent,
                    land: this.params.href,
                    rf: this.params.rf,
                    title: this.params.title,
                    code: this.getRequest('code', '')
                };
                this.socketSend( this.buildMsg('guest_in',params ));
                break;
            case "hello":
                //延迟5秒，有的客户刚进来就分配客户 然后又跑了。让客服端很疑惑（一闪就没有了）
                setTimeout(function(){
                    that.socketSend( that.buildMsg('guest_connect',{} ));
                }, 1500);
                break;
            case "assign_kf":
                config.cs = {
                    t_id: data.data.sn ? data.data.sn : config.cs.t_id,
                    t_name: data.data.name ? data.data.name : config.cs.t_name,
                    avatar: data.data.avatar ? data.data.avatar : config.cs.avatar,
                    wait_num: 0
                };
                // 开始开启自动回复.
                this.autoClose();
                this.bindRepeatEvent();
                // 关闭等待区.
                $('.overflow-message').hide();
                //显示一些系统文字提醒，例如已分配哪个客服
                $(this.output).append(this.renderSystemMessage('客服:' + config.cs.t_name + ',为您服务...'));
                this.renderNickName(config.cs.t_name, config.cs.avatar);
                break;
            case "change_kf":
                config.cs = {
                    t_id: data.data.sn,
                    t_name: data.data.name,
                    avatar: data.data.avatar
                };
                this.bindRepeatEvent();
                this.autoClose();
                break;
            case "reply":
                $(this.output).append( this.renderCsMsg(config.cs.t_name, config.cs.avatar, data.data.content, getCurrentDateTime()) );
                this.scrollToBottom();
                this.autoClose();
                this.bindRepeatEvent();
                break;
            case 'close_guest':
                this.renderCloseChat();
                this.close();
                clearInterval(interval);
                break;
            case 'system':
                if(data.data.hasOwnProperty('code')) {
                    this.close();
                    this.renderCloseChat();
                }
                $(this.output).append(this.renderSystemMessage(data.data.content));
                break;
            case 'kf_logout':
                this.close();
                this.renderCloseChat();
                $(this.output).append(this.renderSystemMessage(data.msg));
                break;
            // 这里要展示在第几位. 这里是等待聊天的队列.
            case 'assign_kf_wait':
                config.cs = {
                    t_id: data.data.sn,
                    t_name: data.data.name,
                    avatar: data.data.avatar,
                    wait_num: parseInt(data.data.wait_num)
                };
                clearInterval(interval);
                // 渲染等待区.
                this.renderWaitMessage(config.cs.wait_num);
                break;
            case 'guest_close':
                this.close();
                this.renderCloseChat();
                break;
        }

        //  交给自定义处理.
        if(this.handle && (typeof this.handle) == 'function') {
            this.handle(data);
        }
    };

    // 自定义显示系统消息.
    socket.prototype.renderSystemMessage = function(msg) {
        if(!this._renderSystemMessage || (typeof this._renderSystemMessage != 'function')) {
            return [
                '<div class="tip-div system">',
                '   <span class="content-tip">',
                '       <span class="line">',msg,'</span>',
                '       <span></span>',
                '   </span>',
                '</div>'
            ].join("");
        }

        return this._renderSystemMessage(msg);
    };

    // 渲染历史记录.
    socket.prototype.renderHistory = function(messages) {
        // 先排序.
        var that = this;

        messages = messages.sort(function (prev,next) {
            return prev.id - next.id;
        });

        // 重定义消息时间.
        config.last_time = messages[0].created_time;

        var contents = messages.map(function (message) {
            var date_info = message.created_time.split(' ');
            var html = '';
            if(date_info[0] != config.last_day) {
                html += that.renderSystemMessage(date_info[0]);
                config.last_day = date_info[0];
            }

            if(message.from_id != config.uuid) {
                html += that.renderCsMsg(message.staff_name, message.cs_avatar, message.content, date_info[1]);
            }else{
                html += that.renderMsg(message.content, date_info[1]);
            }

            return html;
        });

        return contents.join('');
    };

    // 显示等待区.
    socket.prototype.renderWaitMessage = function(num) {
        $('.overflow-message .num').text(num);
        $('.overflow-message').show();
    };

    // 绑定重复发起事件.
    socket.prototype.bindRepeatEvent = function() {
        repeatInterval && clearInterval(repeatInterval);

        // 如果是已经关闭.那就不用管了.
        if(closed || !parseInt(config.style.is_repeat) || !config.style.repeat_setting) {
            return false;
        }

        var that = this;
        // 这里要设置成全局的.
        if(!repeatMessages.length) {
            repeatMessages = JSON.parse(config.style.repeat_setting);
        }

        if(repeatMessages.length <= 0) {
            return false;
        }

        var message = repeatMessages.shift(),
            time = message.time;

        // 先获取一个来进行循环.依次处理. 最后一次处理完成后.
        repeatInterval = setInterval(function () {
            time--;
            if(time > 0) {
                return false;
            }
            // 渲染一条消息
            !closed && $(that.output).append(that.renderCsMsg(config.cs.t_name, config.cs.avatar, message.content, getCurrentDateTime()));
            message = repeatMessages.shift();
            // 这里是否要强制展示.
            that.scrollToBottom();
            // 这里要强制显示出来.
            that.showChat();

            if(!message) {
                clearInterval(repeatInterval);
                // 重置一下.
                config.style.repeat_setting = '[]';
                return false;
            }
            time = message.time;
        }, 1000);
    };

    // 展示聊天区域框.
    socket.prototype.showChat = function() {
        if(this._showChat) {
            return this._showChat();
        }
    }

    // 获取请求信息.
    socket.prototype.getRequest = function (key, default_value){
        var url = location.search; //获取url中"?"符后的字串
        var theRequest = {};
        if (url.indexOf("?") !== -1) {
            var str = url.substr(1);
            var strs = str.split("&");
            for (var i = 0; i < strs.length; i++) {
                theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
            }
        }

        if(key) {
            return theRequest[key] ? theRequest[key] : default_value;
        }

        return theRequest;
    };

    socket.prototype.renderNickName = function(nickname, logo) {
        if(!this._renderNickName && typeof this._renderNickName == 'function') {
            return this._renderNickName(nickname, logo);
        }

        $('.online-header .logo').attr('src', logo);
        $('.online-header span').text(nickname);
        return true;
    };


    window.socket = socket;
})(window);
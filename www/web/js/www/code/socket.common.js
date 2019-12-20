;// 所有聊天框.
(function(window){
    var config = JSON.parse( $(".hidden_wrapper input[name=params]").val() ),
        interval = null;

    if(!config) {
        console.log('未找到初始化配置信息.');
        return false;
    }

    function getCurrentDateTime() {
        var date = new Date();

        return [
            date.getHours(),
            date.getMinutes(),
            date.getSeconds()
        ].map(function (value) {
            return value < 10 ? '0' + value : value;
        }).join(':');
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
        this.system = base_config.hasOwnProperty('system')
            ? base_config.system
            : null;

        this._renderCloseChat = base_config.hasOwnProperty('renderCloseChat')
            ? base_config.renderCloseChat
            : null;
        // 保存socket信息.
        this.ws = null;
        // 表情初始化
        sdEditorEmoj.Init(emojiconfig);
        sdEditorEmoj.setEmoji({type:'div',id: this.emoji});
    };

    // 将聊天框滚动到页面下面.
    socket.prototype.scrollToBottom = function(){
        var ele = $(this.output);
        ele.scrollTop(ele[0].scrollHeight);
    };

    // 初始化整个的页面聊天.
    socket.prototype.init = function() {
        this.initSocket();
        // 绑定界面事件.
        this.eventBind();
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
                $(that.system).text('由于您长时间没有对话，系统已经关闭您的会话');
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

        $(this.closeButton).on('click', function () {

        });
    };

    // 页面发送消息．
    socket.prototype.send = function() {
        var msg = $(this.input).html();
        if(msg.length <= 0) {
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

    // 渲染游客的聊天信息.
    socket.prototype.renderMsg = function (msg, time) {
        var div = '';
        if(this._renderMsg) {
            div = this._renderMsg(msg, time);
        }else{
            // 这里是默认的数据信息.
            div = document.createElement('div');
            div.style.textAlign = "right";
            div.innerHTML = [
                '<div class="content-message online-my-message">\n' +
                '     <div class="message-info">\n' +
                '     <div class="message-name-date"><span class="date">',time,'</span><span>我</span></div>\n' +
                '   <div class="message-message">',msg,'</div>\n' +
                '  </div>\n' +
                '</div>'
            ].join("")
        }

        return div;
    };

    // 渲染客服的聊天数据.
    socket.prototype.renderCsMsg = function(nickname, avatar, msg) {
        var div = '',
            time = getCurrentDateTime();

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
        switch (data.cmd) {
            case "ping":
                this.socketSend({ "cmd":"pong" });
                break;
            case "ws_connect":
                var params = {
                    ua: navigator.userAgent,
                    land: parent.location.href,
                    rf: parent.document.referrer,
                };
                this.socketSend( this.buildMsg('guest_in',params ));
                break;
            case "hello":
                this.socketSend( this.buildMsg('guest_connect',{} ));
                break;
            case "assign_kf":
                config.cs = {
                    t_id: data.data.sn,
                    t_name: data.data.name,
                    avatar: data.data.avatar
                };
                // 开始开启自动回复.
                this.autoClose();
                //显示一些系统文字提醒，例如已分配哪个客服
                break;
            case "change_kf":
                config.cs = {
                    t_id: data.data.sn,
                    t_name: data.data.name,
                    avatar: data.data.avatar
                };
                this.autoClose();
                break;
            case "reply":
                $(this.output).append( this.renderCsMsg(config.cs.t_name, config.cs.avatar, data.data.content) );
                this.scrollToBottom();
                this.autoClose();
                break;
            case 'close_guest':
                this.renderCloseChat();
                this.ws.close();
                clearInterval(interval);
                break;
            case 'system':
                if(data.data.hasOwnProperty('code')) {
                    this.ws.close();
                    this.renderCloseChat();
                }
                $(this.system).text(data.data.content);
                break;
            case 'kf_logout':
                this.ws.close();
                this.renderCloseChat();
                $(this.system).text('客服已下线,请开始新对话或留言');
                break;
            // 这里要展示在第几位. 这里是等待聊天的队列.
            case 'guest_connect_wait':
                clearInterval(interval);
                break;
            case 'guest_close':
                this.ws.close();
                this.renderCloseChat();
                break;
        }

        //  交给自定义处理.
        if(this.handle && (typeof this.handle) == 'function') {
            this.handle(data);
        }
    };

    window.socket = socket;
})(window);
// 这个是界面的主要动画效果. 这个要细分. 先将界面的给弄出来.至于其他的可以先不管.
(function(window){
    var Page = function () {}

    // 初始化函数.
    Page.prototype.init = function () {
        sdEditorEmoj.Init(emojiconfig);
        sdEditorEmoj.setEmoji({
            type: 'div',
            id: "content"
        });
        // 菜单栏切换.
        $(".tab .tab-switch .tab-one").click(function() {
            // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
            $(this).addClass("switch-action").siblings().removeClass("switch-action");
            // parent 父元素 next 下一个兄弟节点  children 子节点
            $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
        });

        // 关闭聊天框.
        $('.icon-guanbi').on('click', function () {
            $('#chatExe .flex1').css({'display': 'none'});

            $('.content-message-active').removeClass('content-message-active');
        });
        
        $('.content-select').on('click', function () {
            var text = $(this).find('span').text();
            $('#content').text(text);
        });
        
        // 渲染在线列表.
        this.renderOnlineList();
        // 渲染等待区.
        this.renderOfflineList();
    };

    // 渲染聊天框. 这里要同时去获取游客的轨迹.
    Page.prototype.renderChat = function (uuid) {
        var user = ChatStorage.getItem(uuid),
            that = this;

        console.dir(user);
        $('#chatExe .flex1').attr('data-uuid', uuid);
        $('#chatExe .flex1 .exe-header-info-left>span:first-child').text(user.nickname);
        // 只保存最新的20条,超过了就不保存了.因为localStorage空间有限制.到时在处理成其他的.
        if(!user.messages || user.messages.length < 1) {
            // 置空.就是没有聊天记录.
            $('.flex1 .exe-content-history .exe-content-history-content').html('');
            $('#chatExe .flex1').css({'display': 'flex'});
        }else{
            // 开始处理剩下的. 循环去处理就可以了. 要定义对应的信息.
            var html = user.messages.map(function (message) {
                if(message.f_id == uuid){
                    return that.renderCustomerMsg(user.nickname, user.avatar, message.content, message.time);
                }

                return that.renderCsMsg('我',  message.content, message.time);
            });

            $('.flex1 .exe-content-history .exe-content-history-content').html(html.join(''));
            $('#chatExe .flex1').css({'display': 'flex'});
        }

        if(!uuid) {
            return true;
        }

        this.renderAccessTrack(uuid);
        this.renderGuestInfo(uuid);
    };

    // 渲染在线列表.
    Page.prototype.renderOnlineList = function (source_uuid) {
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

        // @todo 这里要特殊处理一下.
        var html = online_users.map(function (uuid) {
            var user = ChatStorage.getItem(uuid);
            var class_name =　user && user.new_message >　0 ? 'content-new-message ' : '';

            if(uuid == current_uuid) {
                class_name = class_name + 'content-message-active';
            }

            // 这里有图标展示. 这里要注意一下.
            var icon_types = ['shoji','diannao', 'baidu1'];
            return  [
                '<div class="tab-content-list ', class_name, '" data-uuid="',uuid,'" data-name="',user.nickname,'">',
                '   <div class="', user && user.new_message <= 0 ? '' : 'content-new-message','">',
                '       <i class="iconfont icon-shouji"></i>',
                '       <span>',user.nickname,'</span>',
                '   </div>',
                '   <div>',
                '       <span class="content-list-time">',user.allocationTime,'</span>',
                    user.is_online == 1 ? '<span class="list-flag-online"></span>' : '<span class="list-flag-off-line"></span>',
                '   </div>',
                '</div>'
            ].join("");
        });

        $('.tab-content .online').html(html.join(''));
    };

    // 渲染等待区.
    Page.prototype.renderOfflineList = function() {
        $('.keep-census .wait').text(offline_users.length);

        if(offline_users.length <= 0) {
            $('.tab-content .offline').html('<div class="tab-content-list content-no-message">暂无消息</div>');
            return;
        }

        var html = offline_users.map(function (wait_uuid) {
            var user = ChatStorage.getItem(wait_uuid);

            return [
                '<div class="tab-content-list" data-uuid="',wait_uuid,'"  data-name="',user.nickname,'">',
                '   <div>',
                '       <i class="iconfont icon-shouji"></i>',
                '       <span>',user.nickname,'</span>',
                '   </div>',
                '   <div>',
                '       <span class="content-list-time">',user.allocationTime,'</span>',
                '   </div>',
                '</div>'
            ].join('');
        });

        // 添加下线列表.
        $('.tab-content .offline').html(html.join(''));
    };

    // 将聊天框滚动到底部.
    Page.prototype.scrollToBottom = function () {
        var height = $('.exe-content-history')[0].scrollHeight;

        $('.exe-content-history').scrollTop(height);
    };

    // 渲染游客的消息记录.
    Page.prototype.renderCustomerMsg = function (nickname, avatar, msg, time_str) {
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
    };

    // 渲染客服的消息.
    Page.prototype.renderCsMsg = function (nickname, msg, time_str) {
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
    };

    // 获取时间.
    Page.prototype.getCurrentTimeStr = function () {
        var date = new Date();

        return [
            date.getHours(),
            date.getMinutes(),
            date.getSeconds()
        ].map(function (value) {
            return value < 10 ? '0' + value : value;
        }).join(':');
    };

    // 页面访问轨迹.
    Page.prototype.renderAccessTrack = function(uuid) {
        $('.access-track').html('<div style="text-align: center;color: rgb(179, 181, 185) !important;">暂无</div>');
        $.ajax({
            type: 'POST',
            url: cs_common_ops.buildKFCSurl('/visitor/history'),
            data: {
                uuid: uuid
            },
            dataType: 'json',
            success:function (res) {
                if(res.code != 200) {
                    return $.msg(res.msg);
                }

                // 获取聊天轨迹.
                var html = res.data.map(function (history) {
                    return [
                        '<div style="background-color: #ffffff;padding: 5px 25px;">',
                            '<div>落地页：', history.land_url ? history.land_url : '暂无','　</div>',
                            '<div>来源：', history.referer_url ? history.referer_url : '暂无' ,'　</div>',
                            '<div>接待人：',history.staff_name,'</div>',
                            '<div>来访时间：',history.created_time,'秒</div>',
                        '</div>'
                    ].join('');
                });

                $('.access-track').html(html.join(''));
            }
        });
    };

    // 获取用户的基本信息.
    Page.prototype.renderGuestInfo = function(uuid) {
        var elem = $('#chatExe .flex1[data-uuid="'+ uuid +'"]');

        $.ajax({
            type: 'POST',
            url:  cs_common_ops.buildKFCSurl('/visitor/info'),
            data: {
                uuid: uuid
            },
            dataType: 'json',
            success:function (res) {
                if(res.code != 200) {
                    return $.msg(res.msg);
                }

                var member = res.data.member,
                    history= res.data.history;
                // 批量渲染.
                elem.find('.exe-info .name').text(member && member.name ? member.name : '暂无');
                elem.find('.exe-info .mobile').text(member && member.mobile ?  member.mobile : '暂无');
                elem.find('.exe-info .email').text(member && member.email ? member.email : '暂无');
                elem.find('.exe-info .qq').text(member && member.qq ? member.qq : '暂无');
                elem.find('.exe-info .wechat').text(member && member.wechat ? member.wechat : '暂无');
                elem.find('.exe-info .desc').text(member && member.desc ? member.desc : '暂无');
                // elem.find('.keyword span:last-child').text(history.)
                elem.find('.exe-header-info-left span:last-child').text(history.province + ' ('+ history.client_ip +')');
                elem.find('.land-url span:last-child').text(history.land_url ? history.land_url : '暂无');
                elem.find('.land-url span:last-child').attr('title',history.land_url);
                elem.find('.source span:last-child').text(history.source ? history.source : '暂无');
                elem.find('.source span:last-child').attr('title',history.source);
                elem.find('.referer-url span:last-child').text(history.referer_url ? history.referer_url : '暂无');
                elem.find('.referer-url span:last-child').attr('title',history.referer_url);
            }
        })
    };

    window.Page = Page;
})(window);
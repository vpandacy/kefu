;
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
        $(".edit_content .edit_tab .edit_tabs").click(function() {
            // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
            $(this).addClass("edit_tabs_active").siblings().removeClass("edit_tabs_active");
            // parent 父元素 next 下一个兄弟节点  children 子节点
            $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
        });
        // 设置
        $('.icon-htmal5icon21').click(function () {
            $('.edit_bg').hide();
            $('.edit_content').hide();
        });
        // $('.icon-shezhi').click(function () {
        //     $('.edit_bg').show();
        //     $('.edit_content').show();
        // });
        // 关闭聊天框.
        $('.icon-guanbi').on('click', function () {
            $('#chatExe .flex1').css({'display': 'none'});
            $('.content-message-active').removeClass('content-message-active');
        });
        
        $('.words-content').on('click','.content-select', function () {
            var text = $(this).find('span').attr("title");
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

        var html = online_users.map(function (uuid) {
            var user = ChatStorage.getItem(uuid);
            var class_name =　user && user.new_message >　0 ? 'content-new-message ' : '';

            if(!user) {
                return '';
            }

            if(uuid == current_uuid ) {
                class_name = class_name + ' content-message-active ';
            }

            // 这里有图标展示. 这里要注意一下.
            /**
             * @Array:sourceName : 终端集合
             * @Array:mediaName : 媒体集合
             * **/
            var sourceName = [{id:'1',name:'PC',icon:'icon-diannao01'}, {id:'2',name:'手机',icon:'icon-shouji'}, {id:'3',name:'微信',icon:'icon-z-weixin'}];
            var sourceIcon = sourceName.find(function (item) {
                return item.id == user.source;
            });
            var mediaName = [
                {id: '0', name: '直接访问',icon:'http://cdn1.static.corp.hsh568.cn/logo/直接访问.png'},
                {id: '100', name: '百度',icon:'http://cdn1.static.corp.hsh568.cn/logo/百度.png'},
                {id: '110', name: '360',icon:'http://cdn1.static.corp.hsh568.cn/logo/360.png'},
                {id: '120', name: '搜狗',icon:'http://cdn1.static.corp.hsh568.cn/logo/搜狗.png'},
                {id: '130', name: '神马',icon:'http://cdn1.static.corp.hsh568.cn/logo/神马.png'},
                {id: '140', name: '今日头条',icon:'http://cdn1.static.corp.hsh568.cn/logo/头条.png'},
                {id: '150', name: 'OPPO',icon:'http://cdn1.static.corp.hsh568.cn/logo/oppo.png'},
                {id: '160', name: 'VIVO',icon:'http://cdn1.static.corp.hsh568.cn/logo/vivo.png'},
                {id: '170', name: '小米',icon:'http://cdn1.static.corp.hsh568.cn/logo/小米.png'},
                {id: '180', name: 'WIFI',icon:'http://cdn1.static.corp.hsh568.cn/logo/WIFI.png'},
                {id: '190', name: '趣头条',icon:'http://cdn1.static.corp.hsh568.cn/logo/趣头条.png'},
                {id: '200', name: 'UC',icon:'http://cdn1.static.corp.hsh568.cn/logo/UC.png'},
                {id: '210', name: '一点资讯',icon:'http://cdn1.static.corp.hsh568.cn/logo/一点资讯.png'},
                {id: '220', name: '快手',icon:'http://cdn1.static.corp.hsh568.cn/logo/快手.png'},
                {id: '230', name: '广点通',icon:'http://cdn1.static.corp.hsh568.cn/logo/广点通.png'},
                {id: '240', name: '陌陌',icon:'http://cdn1.static.corp.hsh568.cn/logo/陌陌.png'},
                {id: '250', name: 'WPS',icon:'http://cdn1.static.corp.hsh568.cn/logo/WPS.png'},
                {id: '260', name: '趣看天下',icon:'http://cdn1.static.corp.hsh568.cn/logo/趣看天下.png'},
                {id: '270', name: '知乎',icon:'http://cdn1.static.corp.hsh568.cn/logo/知乎.png'},
                {id: '280', name: '爱奇艺',icon:'http://cdn1.static.corp.hsh568.cn/logo/爱奇艺.png'}
            ];
            var mediaIcon = mediaName.find(function (item) {
                return item.id == user.media;
            });

            var tmp_header = [
                '<div class="tab-content-list ', class_name, '" data-uuid="',uuid,'" data-name="',user.nickname,'">'
            ];
            var tmp_content = [
                '   <div class="', user && user.new_message <= 0 ? '' : 'content-new-message','">',
                '       <i class="iconfont ', sourceIcon.icon ,'"></i>',
                '       <span>',user.nickname,'</span>',
                '   </div>',
                '   <div>',
                '       <span class="content-list-time">',user.allocationTime,'</span>',
                '   <img alt="',mediaIcon.name,'" title="',mediaIcon.name,'"  src="',mediaIcon.icon,'"> ',
                user.is_online == 1 ? '<span class="list-flag-online">在线</span>' : '<span class="list-flag-off-line">离线</span>',
                '   </div>'
            ];

            if( user.new_message > 0 ){
                tmp_content.push( '<div class="corner-marker">' + user.new_message + '</div>' );
            }

            var tmp_footer = [ "</div>" ];
            return tmp_header.join("") + tmp_content.join("") + tmp_footer.join("") ;
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
            if(!user) {
                return '';
            }
            var sourceName = [{id:'1',name:'PC',icon:'icon-diannao01'}, {id:'2',name:'手机',icon:'icon-shouji'}, {id:'3',name:'微信',icon:'icon-z-weixin'}];
            var sourceIcon = sourceName.find(function (item) {
                return item.id == user.source;
            });

            var mediaName = [
                {id: '0', name: '直接访问',icon:'http://cdn1.static.corp.hsh568.cn/logo/直接访问.png'},
                {id: '100', name: '百度',icon:'http://cdn1.static.corp.hsh568.cn/logo/百度.png'},
                {id: '110', name: '360',icon:'http://cdn1.static.corp.hsh568.cn/logo/360.png'},
                {id: '120', name: '搜狗',icon:'http://cdn1.static.corp.hsh568.cn/logo/搜狗.png'},
                {id: '130', name: '神马',icon:'http://cdn1.static.corp.hsh568.cn/logo/神马.png'},
                {id: '140', name: '今日头条',icon:'http://cdn1.static.corp.hsh568.cn/logo/头条.png'},
                {id: '150', name: 'OPPO',icon:'http://cdn1.static.corp.hsh568.cn/logo/oppo.png'},
                {id: '160', name: 'VIVO',icon:'http://cdn1.static.corp.hsh568.cn/logo/vivo.png'},
                {id: '170', name: '小米',icon:'http://cdn1.static.corp.hsh568.cn/logo/小米.png'},
                {id: '180', name: 'WIFI',icon:'http://cdn1.static.corp.hsh568.cn/logo/WIFI.png'},
                {id: '190', name: '趣头条',icon:'http://cdn1.static.corp.hsh568.cn/logo/趣头条.png'},
                {id: '200', name: 'UC',icon:'http://cdn1.static.corp.hsh568.cn/logo/UC.png'},
                {id: '210', name: '一点资讯',icon:'http://cdn1.static.corp.hsh568.cn/logo/一点资讯.png'},
                {id: '220', name: '快手',icon:'http://cdn1.static.corp.hsh568.cn/logo/快手.png'},
                {id: '230', name: '广点通',icon:'http://cdn1.static.corp.hsh568.cn/logo/广点通.png'},
                {id: '240', name: '陌陌',icon:'http://cdn1.static.corp.hsh568.cn/logo/陌陌.png'},
                {id: '250', name: 'WPS',icon:'http://cdn1.static.corp.hsh568.cn/logo/WPS.png'},
                {id: '260', name: '趣看天下',icon:'http://cdn1.static.corp.hsh568.cn/logo/趣看天下.png'},
                {id: '270', name: '知乎',icon:'http://cdn1.static.corp.hsh568.cn/logo/知乎.png'},
                {id: '280', name: '爱奇艺',icon:'http://cdn1.static.corp.hsh568.cn/logo/爱奇艺.png'}
            ];
            var mediaIcon = mediaName.find(function (item) {
                return item.id == user.media;
            });
            return [
                '<div class="tab-content-list" data-uuid="',wait_uuid,'"  data-name="',user.nickname,'">',
                '   <div>',
                '       <i class="iconfont ', sourceIcon.icon ,'"></i>',
                '       <span>',user.nickname,'</span>',
                '   </div>',
                '   <div>',
                '       <span class="content-list-time">',user.allocationTime,'</span>',
                '   <img alt="',mediaIcon.name,'" title="',mediaIcon.name,'"  src="',mediaIcon.icon,'"> ',
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
        //选择这里用JS渲染下，判断是否包含手机号码
        var re = /1\d{10}/;
        if( re.test( msg ) ){
            msg = '<span style="font-size: 18px;color: blue;font-weight: bold;">'
                + msg +'</span>';
        }
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

    // 页面.
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
                if(res.code == -302) {
                    return $.msg(res.msg, false, function(){
                        location.href = res.data.url;
                    });
                }
                if(res.code != 200) {
                    return $.msg(res.msg);
                }

                // 获取聊天轨迹.
                var html = res.data.map(function (history) {
                    return [
                        '<div style="background-color: #ffffff;padding: 5px 25px;overflow: hidden;word-break: break-all;    font-size: 12px;color: darkgray;">',
                            '<div>落地页：', history.land_url ? history.land_url : '暂无','　</div>',
                            '<div>来源：', history.referer_url ? history.referer_url : '暂无' ,'　</div>',
                            '<div>接待人：',history.staff_name,'</div>',
                            '<div>来访时间：',history.created_time,'</div>',
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
                if(res.code == -302) {
                    return $.msg(res.msg, false, function(){
                        location.href = res.data.url;
                    });
                }
                if(res.code != 200) {
                    return $.msg(res.msg);
                }

                var member = res.data.member,
                    history= res.data.history;
                $('.land-url').mousemove(function () {
                    $('.landUrl-copy').show();
                });
                $('.land-url').mouseout(function () {
                    $('.landUrl-copy').hide();
                });
                $('.referer-url').mousemove(function () {
                    $('.referer-url-copy').show();
                });
                $('.referer-url').mouseout(function () {
                    $('.referer-url-copy').hide();
                });
                $('.landUrl-copy').attr('data-clipboard-text', history.land_url)
                $('.referer-url-copy').attr('data-clipboard-text', history.referer_url ? history.referer_url : '');
                new ClipboardJS('.landUrl-copy');
                new ClipboardJS('.referer-url-copy');
                // 批量渲染.
                elem.find('.exe-info .name').text(member && member.name ? member.name : '暂无');
                elem.find('.exe-info .mobile').text(member && member.mobile ?  member.mobile : '暂无');
                elem.find('.exe-info .email').text(member && member.email ? member.email : '暂无');
                elem.find('.exe-info .qq').text(member && member.qq ? member.qq : '暂无');
                elem.find('.exe-info .wechat').text(member && member.wechat ? member.wechat : '暂无');
                elem.find('.exe-info .desc').text(member && member.desc ? member.desc : '暂无');
                // elem.find('.keyword span:last-child').text(history.)
                elem.find('.exe-header-info-left span:last-child').text(history.province + ' ('+ history.client_ip +')');
                elem.find('.land-url .land-url-url').text(history.land_title ? history.land_title : history.land_url);
                elem.find('.land-url .land-url-url').attr('title', history.land_url);
                elem.find('.land-url .land-url-url').attr('href', history.land_url);

                elem.find('.source span:last-child').text(history.keyword ? history.keyword : '暂无');
                elem.find('.source span:last-child').attr('title',history.keyword);
                elem.find('.referer-url span:nth-child(2)').text(history.referer_url ? history.referer_url : '暂无');
                elem.find('.referer-url span:nth-child(2)').attr('title',history.referer_url);
                var canalName = [
                    {id:'0',name:'直接访问'},
                    {id:'100',name:'百度'},
                    {id:'110',name:'360'},
                    {id:'120',name:'搜狗'},
                    {id:'130',name:'神马'},
                    {id:'140',name:'今日头条'},
                    {id:'150',name:'OPPO'},
                    {id:'160',name:'VIVO'},
                    {id:'170',name:'小米'},
                    {id:'180',name:'WIFI'},
                    {id:'190',name:'趣头条'},
                    {id:'200',name:'UC'},
                    {id:'210',name:'一点资讯'},
                    {id:'220',name:'快手'},
                    {id:'230',name:'广点通'},
                    {id:'240',name:'陌陌'},
                    {id:'250',name:'WPS'},
                    {id:'260',name:'趣看天下'},
                    {id:'270',name:'知乎'},
                    {id:'280',name:'爱奇艺'}
                ];
                var obj=canalName.find(function (item) {
                    return item.id === history.referer_media;
                });
                elem.find('.canal-url span:last-child').text(obj.name ? obj.name : '暂无');
                elem.find('.canal-url span:last-child').attr('title',obj.name);
            },
        });
    };

    window.Page = Page;
})(window);
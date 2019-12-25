// 管理右键菜单的事件.
(function(window){
    // 这里是右键菜单.
    var Contextmenu = function (elem, container, page) {
        // 隐藏的右键菜单.
        this.ele = $(elem);
        // 容器
        this.container = $(container);
        // 页面操作.
        this.page = page;
    };

    Contextmenu.prototype.init = function() {
        this.eventBind();
    };

    Contextmenu.prototype.eventBind = function () {
        var that = this;
        $(document).on('click', function () {
            that.ele.css({
                display: 'none'
            });
        });


        // 监听鼠标右键.
        this.container.on('contextmenu', '.tab-content-list', function (event) {
            // 阻止事件发生.
            event.preventDefault();

            if($(this).hasClass('content-no-message')) {
                return true;
            }

            var uuid = $(this).attr('data-uuid');

            that.showMenu(uuid, event);
        });
    };

    Contextmenu.prototype.showMenu = function (uuid, event) {
        var x = ((parseInt(event.clientX) + 100) <= window.innerWidth) ? event.clientX : event.clientX - 98,
            y = ((parseInt(event.clientY) + 140) <= window.innerHeight) ? event.clientY : event.clientY - 138,
            user = ChatStorage.getItem(uuid),
            that = this;

        // 展示出来.并将uuid存成局部变量.
        this.ele.css({
            left: x + 'px',
            top : y + 'px',
            display: 'block'
        });

        // 先取消并注册事件.
        $('#menu a').off('click').on('click', function () {
            var event = $(this).attr('data-event');

            switch (event) {
                case 'edit':
                    that.edit(user);
                    break;
                // 关闭聊天操作.
                case 'close':
                    that.close(user);
                    break;
                // 加入黑名单.
                case 'black':
                    that.joinBlackList(user);
                    break;
                // 转让游客.
                case 'transfer':
                    that.transfer(user);
                    break;
            }
            return false;
        });
    };

    // 编辑事件.
    Contextmenu.prototype.edit = function (user) {
        alert('您点击了编辑,uuid:' + user.uuid);
    };

    // 聊天关闭事件.
    Contextmenu.prototype.close = function (user) {
        var current_uuid = $('.content-message-active').attr('data-uuid'),
            that = this;

        // 游客已经下线了.直接删除就可以了.
        if(!user.is_online) {
            // 这里要删除游客信息,并缩小减少online_users.
            if(user.uuid == current_uuid) {
                $('#chatExe .flex1').css({'display': 'none'});
            }

            online_users = online_users.filter(function (curr, curr_index) {
                return user.uuid != curr;
            });

            // 重新渲染.
            ChatStorage.removeItem(user.uuid);
            // 隐藏.
            that.ele.css({display: 'none'});
            that.page.renderOnlineList();
            return false;
        }

        $.confirm('您确认要关闭与游客:' + user.nickname + '的聊天吗?是否继续?',function(){
            var index = $.loading(1, {shade: .5});
            $.ajax({
                type: 'POST',
                url: cs_common_ops.buildKFCSurl('/visitor/close'),
                data: {
                    uuid: user.uuid
                },
                dataType: 'json',
                success:function (res) {
                    $.close(index);
                    if(res.code != 200) {
                        return $.msg(res.msg);
                    }

                    // 这里要删除游客信息,并缩小减少online_users.
                    if(user.uuid == current_uuid) {
                        $('#chatExe .flex1').css({'display': 'none'});
                    }

                    online_users = online_users.filter(function (curr, curr_index) {
                        return user.uuid != curr;
                    });
                    // 隐藏.
                    that.ele.css({display: 'none'});
                    // 重新渲染.
                    ChatStorage.removeItem(user.uuid);
                    that.page.renderOnlineList();
                },
                error: function () {
                    $.close(index)
                    // 隐藏.
                    that.ele.css({display: 'none'});
                }
            });
        });
    };

    // 加入黑名单.
    Contextmenu.prototype.joinBlackList = function (user) {
        var current_uuid = $('.content-message-active').attr('data-uuid');
        var that = this;
        $.confirm('您确认要将该游客拉入黑名单吗？凡在黑名单中的游客将无法发起聊天.是否继续?', function () {
            var index = $.loading(1, {shade: .5});
            $.ajax({
                type: 'POST',
                url: cs_common_ops.buildKFCSurl('/visitor/blacklist'),
                data: {
                    uuid: user.uuid
                },
                dataType: 'json',
                success:function (res) {
                    $.close(index);
                    if(res.code != 200) {
                        return $.msg(res.msg);
                    }

                    // 这里要删除游客信息,并缩小减少online_users.
                    if(user.uuid == current_uuid) {
                        $('#chatExe .flex1').css({'display': 'none'});
                    }

                    online_users = online_users.filter(function (curr, curr_index) {
                        return curr != current_uuid;
                    });

                    that.page.renderOnlineList();
                },
                error: function () {
                    $.close(index)
                }
            });
        });
    };

    // 游客转让.
    Contextmenu.prototype.transfer = function (user) {
        var that = this;
        // 先获取所有的在线的客服.
        $.ajax({
            type: 'GET',
            url: cs_common_ops.buildKFCSurl('/user/online'),
            data: null,
            dataType: 'json',
            success:function (res) {
                if(res.code != 200) {
                    return $.msg(res.msg);
                }
                that.renderSelectCsMenuAndBindEvent(res.data, user)
            },
            error: function () {
                $.msg('请求失败');
            }
        })
    };
    
    
    Contextmenu.prototype.renderSelectCsMenuAndBindEvent = function (customer_services, user) {
        var current_uuid = $('.content-message-active').attr('data-uuid'),
            that = this,
            html = customer_services.map(function (ele) {
            return '<option value="' + ele.id +'">' + ele.name +'</option>';
        });

        var index = $.open({
            content:'<select name="cs">' + html.join('') +'</select>',
            title: '请选择客服',
            btn: ['确定','取消'],
            yes: function () {
                $.close(index);
                var cs_id = $('[name=cs]').val();
                index = $.loading(1, {shade: .5});

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: cs_common_ops.buildKFCSurl('/visitor/transfer'),
                    data: {
                        uuid: user.uuid,
                        cs_id: cs_id
                    },
                    success: function (res) {
                        $.close(index);
                        if(res.code != 200) {
                            return $.msg(res.msg);
                        }

                        $.msg(res.msg,true, function () {
                            if(user.uuid == current_uuid) {
                                $('#chatExe .flex1').css({'display': 'none'});
                            }

                            online_users = online_users.filter(function (curr) {
                                return curr != user.uuid;
                            });

                            ChatStorage.removeItem(user.uuid);
                            that.page.renderOnlineList();
                        });
                    },
                    error: function () {
                        $.close(index);
                    }
                })
            },
            btn2: function () {
                $.close(index);
            }
        });
    }

    window.Contextmenu = Contextmenu;
})(window);
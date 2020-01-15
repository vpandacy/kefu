;
var sourceName = [{id:'1',name:'PC',icon:'icon-diannao01'}, {id:'2',name:'手机',icon:'icon-shouji'}, {id:'3',name:'微信',icon:'icon-z-weixin'}];
var mediaName = [
    {id: '0', name: '直接访问',icon:'http://static.kefu.test.hsh568.cn/logo/直接访问.png'},
    {id: '100', name: '百度',icon:'http://static.kefu.test.hsh568.cn/logo/百度.png'},
    {id: '110', name: '360',icon:'http://static.kefu.test.hsh568.cn/logo/360.png'},
    {id: '120', name: '搜狗',icon:'http://static.kefu.test.hsh568.cn/logo/搜狗.png'},
    {id: '130', name: '神马',icon:'http://static.kefu.test.hsh568.cn/logo/神马.png'},
    {id: '140', name: '今日头条',icon:'http://static.kefu.test.hsh568.cn/logo/头条.png'},
    {id: '150', name: 'OPPO',icon:'http://static.kefu.test.hsh568.cn/logo/oppo.png'},
    {id: '160', name: 'VIVO',icon:'http://static.kefu.test.hsh568.cn/logo/vivo.png'},
    {id: '170', name: '小米',icon:'http://static.kefu.test.hsh568.cn/logo/小米.png'},
    {id: '180', name: 'WIFI',icon:'http://static.kefu.test.hsh568.cn/logo/WIFI.png'},
    {id: '190', name: '趣头条',icon:'http://static.kefu.test.hsh568.cn/logo/趣头条.png'},
    {id: '200', name: 'UC',icon:'http://static.kefu.test.hsh568.cn/logo/UC.png'},
    {id: '210', name: '一点资讯',icon:'http://static.kefu.test.hsh568.cn/logo/一点资讯.png'},
    {id: '220', name: '快手',icon:'http://static.kefu.test.hsh568.cn/logo/快手.png'},
    {id: '230', name: '广点通',icon:'http://static.kefu.test.hsh568.cn/logo/广点通.png'},
    {id: '240', name: '陌陌',icon:'http://static.kefu.test.hsh568.cn/logo/陌陌.png'},
    {id: '250', name: 'WPS',icon:'http://static.kefu.test.hsh568.cn/logo/WPS.png'},
    {id: '260', name: '趣看天下',icon:'http://static.kefu.test.hsh568.cn/logo/趣看天下.png'},
    {id: '270', name: '知乎',icon:'http://static.kefu.test.hsh568.cn/logo/知乎.png'},
    {id: '280', name: '爱奇艺',icon:'http://static.kefu.test.hsh568.cn/logo/爱奇艺.png'}
];
var merchant_user_track_ops = {
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        layui.use(['table','laydate'], function(){
            var table = layui.table,
                laydate = layui.laydate;

            laydate.render({
                elem: '#time',
                type: 'datetime',
                range: '~'
            });

            table.render(merchant_common_ops.buildLayuiTableConfig({
                elem: '#trackTable'
                ,url: merchant_common_ops.buildMerchantUrl('/user/track/index')
                ,where: {
                    time: $('#time').val(),
                    group_id: $('[name=group_id]').val(),
                    mobile: $('#mobile').val(),
                    url: $('#url').val(),
                    staff_id: $('#staff_id').val(),
                    qq: $('#qq').val(),
                    email: $('#email').val(),
                    wechat: $('#wechat').val()
                }
                ,defaultToolbar: ['filter','exports']
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {field:'id', width:80, title: '序号'}
                    ,{field:'uuid',  title: '访客名称', templet:function (row) {
                        return row.uuid.substr(row.uuid.length - 12);
                    }}
                    ,{field:'uuid',  title: '访客编号'}
                    ,{field:'client_ip',  title: 'IP地址'}
                    ,{field:'staff_name', title: '客服'}
                    ,{field:'style_title',  title: '风格分组'}
                    ,{field:'member_name', title: '会员名'}
                    ,{field:'referer_url', title: '来源',templet:function (row) {
                        return row.referer_url == '' ? '暂无' : [
                            '<a title="', row.referer_url, '" href="', row.referer_url, '" target="_blank">',row.referer_url,'</a>'
                        ].join('');
                    }}
                    ,{field:'land_url', title: '落地页', templet: function (row) {
                        return row.land_url == '' ? '暂无' : [
                            '<a title="', row.land_url, '" href="', row.land_url, '" target="_blank">',row.land_url,'</a>'
                        ].join('');
                    }}
                    ,{field: 'source', title: '终端来源', templet:function (row) {
                        var sources_map = {
                            0: '暂无',
                            1: 'PC',
                            2: '手机',
                            3: '微信'
                        };
                        return sources_map[row.source];
                    }}
                    ,{field:'chat_duration', title: '聊天时长', templet:function (row) {
                        var sec = row.chat_duration % 60,
                            min = parseInt(row.chat_duration - sec) / 60;
                        return min + '分' + sec + '秒';
                    }}
                    ,{field:'created_time', width: 170, title: '来访时间'}
                    ,{title: '操作', toolbar: '#trackTool',fixed: 'right',width: 100}
                ]]
                ,id: 'trackTable'
            }));

            // 您点击了查看详情.
            var historyId;
            table.on('tool(trackTable)', function (event) {
                historyId = event.data.id;
                if(event.event != 'see') {
                    return false;
                }
                // 这里开始弹层
                $('.layui-table-view').append('<div class="trackTable_big"></div>');
                var fromHtml = "<div class='trackTable_toop'>" +
                    "<div class='tab'>" +
                    "<div class='tabs_all'><div class='tabs tabs_active'>对话记录</div><div class='tabs'>详细信息</div><div class='tabs'>访问轨迹</div></div>" +
                    "<div class='tabs_tools'><div class='iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-'></div>"+
                    "<div class='iconfont icon-jiantou9'></div>"+
                    "<div class='iconfont icon-guanbi'></div></div>"+
                    "</div>" +
                    "<div class='tabs_content'>" +
                    "<div class='content_assgin'></div>" +
                    "<div class='content_information dis_none'>" +
                    "</div><div class='dis_none'>" +
                    "<div class='router_table'></div></div>"+
                    "</div>"+
                    "</div>\n";
                $('.trackTable_big').append(fromHtml);
                trackChat(historyId);
                trackDetail(historyId);
                trackHistory(historyId);
                function trackChat(historyId) {
                    $.post('/merchant/user/track/chat',{history_id:historyId},function (res) {
                        if(res.code === 200){
                            $('.content_assgin').html("");
                            res.data.length === 0 ? $('.content_assgin').text('暂无记录'):res.data.forEach(function (item) {
                                if(item.uuid === item.from_id){
                                    $('.content_assgin').append(
                                        " <div class='assgin_info'><div class='assgin_title'>"+item.nickname+"&nbsp;&nbsp;"+item.created_time+"</div>" +
                                        " <div class='assgin_content'>"+item.content+"</div></div>");
                                }else {
                                    $('.content_assgin').append(
                                        " <div class='assgin_info'><div class='assgin_title as_title_my'>"+item.cs_name+"&nbsp;&nbsp;"+item.created_time+"</div>" +
                                        " <div class='assgin_content'>"+item.content+"</div></div>");
                                }
                            })
                            return;
                        }
                        layer.msg('请联系管理员');
                    });
                }
                function trackDetail(historyId) {
                    $.post('/merchant/user/track/detail',{history_id:historyId},function (res) {
                        if(res.code === 200){
                            var source_name= sourceName.find(function (item) {
                                return item.id==res.data.source;
                            })
                            var media_name= mediaName.find(function (item) {
                                return item.id==res.data.referer_media;
                            });
                            $('.content_information').html("");
                            res.data.length === 0 ? $('.content_information').text('暂无信息'): $('.content_information').append("<div><span class='information_title'>开始时间：</span><span>"+res.data.created_time+"</span></div>" +
                                "<div><span class='information_title'>结束时间：</span><span>"+res.data.closed_time+"</span></div>" +
                                "<div><span class='information_title'>对话时长：</span><span>"+res.data.chat_duration +" 秒</span></div>" +
                                "<div><span class='information_title'>地区：</span><span>"+res.data.province_str+"</span></div>" +
                                "<div><span class='information_title'>访客IP：</span><span>"+res.data.client_ip+"</span></div>" +
                                "<div><span class='information_title'>终端：</span><span>"+source_name.name+"</span></div>" +
                                "<div><span class='information_title'>消息类型：</span><span>在线消息</span></div>" +
                                "<div><span class='information_title'>访问来源：</span><span>"+media_name.name+"</span></div>" +
                                "<div><span class='information_title'>关键词：</span><span>暂无</span></div>" +
                                "<div><span class='information_title'>落地页：</span><a target='_blank' href='"+ res.data.land_url +"'>"+res.data.land_url+"</a></div>");
                        }
                    });
                }
                function trackHistory(historyId) {
                    //执行渲染
                    table.render(merchant_common_ops.buildLayuiTableConfig({
                        elem: '.router_table'
                        ,url: merchant_common_ops.buildMerchantUrl('/user/track/history')
                        ,page: false
                        ,method:'post'
                        ,height: 500
                        ,where: {history_id:historyId}
                        ,cellMinWidth: 90 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                        ,cols: [[
                            {field:'created_time', width:170, title: '访问时间',fixed: 'left'}
                            ,{field:'land_url', width:190, title: '访问地址', templet: function (row) {
                                    return '<a title="' + row.land_url +'" href="' + row.land_url +'" target="_blank">' + row.land_url + '</a>';
                                }}
                            ,{field:'chat_duration', title: '停留时长'}
                        ]]
                    }));
                }
                // 菜单栏切换.
                $(".trackTable_toop .tab .tabs").click(function() {
                    // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
                    $(this).addClass("tabs_active").siblings().removeClass("tabs_active");
                    // parent 父元素 next 下一个兄弟节点  children 子节点
                    $(this).parent().parent().next().children().eq($(this).index()).show().siblings().hide();
                });
                $('.trackTable_toop .tab .icon-guanbi').click(function () {
                    $('.trackTable_big').hide();
                });
                $('.icon-changyongtubiao-xianxingdaochu-zhuanqu-').click(function () {
                    $.post(merchant_common_ops.buildMerchantUrl('/user/track/index'),{group_id: 0},function (res){
                       let index = res.data.findIndex((item)=>item.id === historyId);
                       historyId = res.data[index-1].id;
                        trackChat(historyId);
                        trackDetail(historyId);
                        trackHistory(historyId);
                        $('div[lay-id="trackTable"]').find('tbody tr').eq(index-1).addClass('layui-table-click');
                        $('div[lay-id="trackTable"]').find('tbody tr').eq(index).removeClass('layui-table-click');
                    });
                });
                $('.icon-jiantou9').click(function () {
                    $.post(merchant_common_ops.buildMerchantUrl('/user/track/index'),{group_id: 0},function (res){
                        let index = res.data.findIndex((item)=>item.id === historyId);
                        historyId = res.data[index+1].id;
                        trackChat(historyId);
                        trackDetail(historyId);
                        trackHistory(historyId);
                        $('div[lay-id="trackTable"]').find('tbody tr').eq(index+1).addClass('layui-table-click');
                        $('div[lay-id="trackTable"]').find('tbody tr').eq(index).removeClass('layui-table-click');
                    });
                });
            });
            // 筛选
            $('.screen_message').click(function () {
               $(this).children('.layui-edge').toggleClass('layui-edge-active');
               !($(this).children('.layui-edge').hasClass('layui-edge-active')) ? $(this).next().hide() : $(this).next().show();
            });
        });
    },
};

$(document).ready(function () {
    merchant_user_track_ops.init();
});
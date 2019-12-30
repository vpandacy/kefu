;
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
                    group_id: $('[name=group_id]').val()
                }
                ,defaultToolbar: ['filter','exports']
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {field:'id', width:80, title: '序号'}
                    ,{field:'client_ip',  title: 'IP地址'}
                    ,{field:'uuid',  title: '访客编号'}
                    ,{field:'staff_name', title: '客服'}
                    ,{field:'style_title',  title: '风格分组'}
                    ,{field:'member_name', title: '会员名'}
                    ,{field:'referer_url', title: '来源',templet:function (row) {
                        return row.referer_url == '' ? '暂无' : row.referer_url;
                    }}
                    ,{field:'land_url', title: '落地页'}
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
                        return row.chat_duration + '秒';
                    }}
                    ,{field:'created_time', title: '来访时间'}
                    ,{title: '操作', toolbar: '#trackTool'}
                ]]
                ,id: 'trackTable'
            }));

            // 您点击了查看详情.
            table.on('tool(trackTable)', function (event) {
                let historyId = event.data.id;
                if(event.event != 'see') {
                    return false;
                }
                // 这里开始弹层
                $('.layui-table-view').append('<div class="trackTable_big"></div>');
                var fromHtml = "<div class='trackTable_toop'>" +
                    "<div class='tab'>" +
                    "<div class='tabs tabs_active'>对话记录</div><div class='tabs'>详细信息</div><div class='tabs'>访问轨迹</div>" +
                    "<div class='iconfont icon-guanbi'></div>"+
                    "</div>" +
                    "<div class='tabs_content'>" +
                    "<div class='content_assgin'></div>" +
                    "<div class='dis_none'>2</div>" +
                    "<div class='dis_none'>3</div>" +
                    "</div>"+
                    "</div>\n";
                $('.trackTable_big').append(fromHtml);
                $.post('/merchant/user/track/chat',{history_id:historyId},function (res) {
                    if(res.code === 200){
                        res.data.forEach(function (item) {
                            if(item.uuid === item.from_id){
                                $('.content_assgin').append(
                                    " <div class='assgin_info'><div class='assgin_title'>"+item.nickname+"&nbsp;&nbsp;"+item.created_time+"</div>" +
                                    " <div class='assgin_content'>"+item.content+"</div></div>");
                            }else {
                                $('.content_assgin').append(
                                    " <div class='assgin_info'><div class='assgin_title as_title_my'>"+item.nickname+"&nbsp;&nbsp;"+item.created_time+"</div>" +
                                    " <div class='assgin_content'>"+item.content+"</div></div>");
                            }
                        })
                        return;
                    }
                    layer.msg('请联系管理员');
                });
                // 菜单栏切换.
                $(".trackTable_toop .tab .tabs").click(function() {
                    // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
                    $(this).addClass("tabs_active").siblings().removeClass("tabs_active");
                    // parent 父元素 next 下一个兄弟节点  children 子节点
                    $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
                });
                $('.trackTable_toop .tab .icon-guanbi').click(function () {
                    $('.trackTable_big').html("");
                });
            });
        });
    }
};

$(document).ready(function () {
    merchant_user_track_ops.init();
});
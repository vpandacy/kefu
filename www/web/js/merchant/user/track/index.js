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
                if(event.event != 'see') {
                    return false;
                }

                // 这里开始弹层
                $.msg('您点击了查看详情');
            });
        });
    }
};

$(document).ready(function () {
    merchant_user_track_ops.init();
});
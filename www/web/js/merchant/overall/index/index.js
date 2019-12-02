;
var merchant_overall_index_ops = {
    init: function () {
        layui.use('table', function(){
            var table = layui.table;

            table.render({
                elem: '#test'
                ,url:'/merchant/overall/index/list'
                ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,cols: [[
                    {type:'checkbox', fixed: 'left'},
                    {field:'id', width:80, title: '序号'}
                    ,{field:'words', title: '常用语'}
                    ,{field:'created_time', width: 100, title: '创建时间', sort: true}
                    ,{field: 'right', title:'操作', toolbar: '#barDemo', width:150, fixed: 'right'}
                ]]
                // ,id: 'testReload'
                ,page: true

            });
            var $ = layui.$, active = {
                reload: function(){
                    var demoReload = $('#demoReload');
                    //执行重载
                    table.reload('testReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        },where: {
                            id: demoReload.val()
                        }
                    }, 'data');
                }
            };
            $('.demoTable .layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        });

    }
};

$(document).ready(function () {
    merchant_overall_index_ops.init();
});

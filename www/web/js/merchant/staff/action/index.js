layui.use('table', function(){
    var table = layui.table;

    table.render({
        elem: '#test'
        ,url:'/css/merchant/staff/index/dome.json'
        ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
        ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
        ,cols: [[
            {type:'checkbox', fixed: 'left'},
            {field:'id', width:80, title: '账号'}
            ,{field:'username', width:80, title: '工号'}
            ,{field:'sex', width:80, title: '姓名'}
            ,{field:'city', width:80, title: '部门'}
            ,{field:'sign', title: '岗位', minWidth: 100} //minWidth：局部定义当前单元格的最小宽度，layui 2.2.1 新增
            ,{field:'experience', title: '身份'}
            ,{field:'score', title: '入职时间', sort: true}
            ,{field:'classify', title: '状态'}
            ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:150, fixed: 'right'}
        ]]
        ,id: 'testReload'
        ,page: true
    });
    var $ = layui.$, active = {
        reload: function(){
            var demoReload = $('#demoReload');
            //执行重载
            table.reload('testReload', {
                page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
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
//表单
layui.use('form', function(){
    var form = layui.form;

    //监听提交
    form.on('submit(formDemo)', function(data){
        layer.msg(JSON.stringify(data.field));
        return false;
    });
});
// 颜色选择器
layui.use('colorpicker', function(){
    var colorpicker = layui.colorpicker;
    //渲染
    colorpicker.render({
        elem: '#test2'  //绑定元素
    });
});
// 树形菜单
layui.use('tree', function(){
    var tree = layui.tree;

    //渲染
    var inst1 = tree.render({
        elem: '#test3'  //绑定元素
        ,showCheckbox:true
        ,data: [{
            title: '江西' //一级菜单
            ,children: [{
                title: '南昌' //二级菜单
                ,children: [{
                    title: '高新区' //三级菜单
                    //…… //以此类推，可无限层级
                }]
            }]
        },{
            title: '陕西' //一级菜单
            ,children: [{
                title: '西安' //二级菜单
            }]
        }]
    });
});
// 文档编辑器
layui.use('layedit', function(){
    var layedit = layui.layedit;
    layedit.build('demo4'); //建立编辑器
});
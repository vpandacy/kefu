layui.use('table', function(){
    var table = layui.table;
    table.render({
        elem: '#test'
        ,url:'http://www.kefu.dev.hsh568.cn/css/merchant/staff/index/dome.json'
        ,toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
        ,defaultToolbar: []
        ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
        ,cols: [[
            {type:'checkbox', fixed: 'left'}
            ,{field:'id', width:80, title: '账号'}
            ,{field:'username', width:80, title: '工号'}
            ,{field:'sex', width:80, title: '姓名'}
            ,{field:'city', width:80, title: '部门'}
            ,{field:'sign', title: '岗位', minWidth: 100} //minWidth：局部定义当前单元格的最小宽度，layui 2.2.1 新增
            ,{field:'experience', title: '身份'}
            ,{field:'score', title: '入职时间', sort: true}
            ,{field:'classify', title: '状态'}
            ,{fixed: 'right', title:'操作', toolbar: '#barDemo', fixed: 'right'}
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
    $('.layui-table-box').append('<div class="filter_panel dis_none" >' +
        '<form class="layui-form layui-form-pane " action="">\n' +
        '                    <div class="layui-form-item">\n' +
        '                        <label class="layui-form-label">输入框</label>\n' +
        '                        <div class="layui-input-block">\n' +
        '                            <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                    <div class="layui-form-item">\n' +
        '                        <label class="layui-form-label">密码框</label>\n' +
        '                        <div class="layui-input-block">\n' +
        '                            <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                    <div class="layui-form-item">\n' +
        '                        <label class="layui-form-label">选择框</label>\n' +
        '                        <div class="layui-input-block">\n' +
        '                            <select name="city" lay-verify="required">\n' +
        '                                <option value=""></option>\n' +
        '                                <option value="0">北京</option>\n' +
        '                                <option value="1">上海</option>\n' +
        '                                <option value="2">广州</option>\n' +
        '                                <option value="3">深圳</option>\n' +
        '                                <option value="4">杭州</option>\n' +
        '                            </select>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                    <div class="layui-form-item">\n' +
        '                        <label class="layui-form-label">文件上传</label>\n' +
        '                        <div class="layui-input-block">\n' +
        '                            <button type="button" class="layui-btn" id="test1">\n' +
        '                                <i class="layui-icon">&#xe67c;</i>上传图片\n' +
        '                            </button>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                    <div class="layui-form-item" pane>\n' +
        '                        <label class="layui-form-label">复选框</label>\n' +
        '                        <div class="layui-input-block">\n' +
        '                            <input type="checkbox" name="like[write]" title="写作">\n' +
        '                            <input type="checkbox" name="like[read]" title="阅读" checked>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                    <div class="layui-form-item" pane>\n' +
        '                        <label class="layui-form-label">开关</label>\n' +
        '                        <div class="layui-input-block">\n' +
        '                            <input type="checkbox" name="switch" lay-skin="switch">\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                    <div class="layui-form-item" pane>\n' +
        '                        <label class="layui-form-label">单选框</label>\n' +
        '                            <div class="layui-input-block"> ' +
                                '<input type="radio" name="sex" value="男" title="男">' +
                                ' <input type="radio" name="sex" value="女" title="女" checked>' +
                            ' </div>\n' +
        '                    </div>\n' +
        '                    <div class="layui-form-item layui-form-text">\n' +
        '                        <label class="layui-form-label">文本域</label>\n' +
        '                        <div class="layui-input-block">\n' +
        '                            <textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                    <div class="layui-form-item">\n' +
        '                        <div class="layui-input-inline">\n' +
        '                            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>\n' +
        '                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                </form>' +
        '</div>');
        // $('.filter_panel').show().height('500px');
        // $('.filter_panel').stopPropagation();
        $(".fa-glass").delegate($('.filter_panel'),"click",function(event){
            event.stopPropagation();
            $(".filter_panel").slideToggle();
        });
        // 点击除弹出层别的地方隐藏
    $(document).click(function(e){
        var target = $(e.target);
        if(target.closest(".filter_panel").length != 0) return;
        $(".filter_panel").hide();
    });
    // $('body').click(function () {
    //     $('.filter_panel').height('0px').hide();
    // });
    layui.use('form', function(){
        var form = layui.form;

        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            return false;
        });
        form.render();
    });
});
<?php
use \common\services\GlobalUrlService;
use \common\components\helper\StaticAssetsHelper;
//StaticAssetsHelper::includeAppCssStatic( GlobalUrlService::buildWwwStaticUrl("/css/merchant/staff/index/index.css"),www\assets\MerchantAsset::className() )
?>
<!--  表格用的layui 具体配置参考：https://www.layui.com/demo/table/auto.html -->
<div id="staff_index_index">
    <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
        'bar_menu'  =>  'chat',
        'current_menu'  =>  'download'
    ])?>
    <div class="tab_staff_content">
        <div class="demoTable" style="    text-align: left;margin:10px 0px;">
            <button class="layui-btn" data-type="reload">搜索</button>
            <div class="layui-inline">
                <input class="layui-input" name="id" id="demoReload" autocomplete="off">
            </div>
        </div>
        <table class="layui-hide" id="test"></table>
    </div>
</div>
<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm" lay-event="getCheckData">添加</button>
        <button class="layui-btn layui-btn-sm" lay-event="isAll">恢复</button>
    </div>
</script>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script src="<?=GlobalUrlService::buildStaticUrl("/layui/v2.5/layui.all.js");?>"></script>
<script type="text/javascript">
    layui.use('table', function(){
        var table = layui.table;

        table.render({
            elem: '#test'
            ,url:'<?=GlobalUrlService::buildKFStaticUrl("/css/merchant/staff/index/dome.json");?>'
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
</script>
//点击鼠标右键响应函数
//tab-content-list
var dom = '';
var tab = {
    list: function (e) {
        dom =e;
        //对右键菜单进行定位
        if(e.clientX+100<=innerWidth){
            var x = e.clientX;
        }
        else{
            var x = e.clientX - 98;
        }
        if(e.clientY+140<=innerHeight){
            var y = e.clientY;
        }
        else{
            var y = e.clientY - 138;
        }

        if(document.getElementById('menu')) {
            document.getElementById('menu').style.left=x+'px';
            document.getElementById('menu').style.top=y+'px';
            document.getElementById('menu').style.display='block';
        }
        //取消默认的浏览器自带右键
        e.preventDefault();
    },
    deleteDom: function () {
        dom.path[1].removeChild(dom.path[0]);
    },
    listHide: function () {
        if(document.getElementById('menu')) {
            document.getElementById('menu').style.display='none';
        }
    }
};
$(function () {
    $(".tab .tab-switch .tab-one").click(function() {
        // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
        $(this).addClass("switch-action").siblings().removeClass("switch-action");
        // parent 父元素 next 下一个兄弟节点  children 子节点
        $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
    });
})


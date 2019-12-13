//点击鼠标右键响应函数
//tab-content-list
$(function () {
    $(".tab .tab-switch .tab-one").click(function() {
        // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
        $(this).addClass("switch-action").siblings().removeClass("switch-action");
        // parent 父元素 next 下一个兄弟节点  children 子节点
        $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
    });
})


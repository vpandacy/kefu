;
var online_ops = {
    // 表情初始化
    emojInit : function () {
        sdEditorEmoj.Init(emojiconfig);
        sdEditorEmoj.setEmoji({type: 'div', id: "content"});
    },
    // 菜單TAB切換
    tabSwitch : function () {
        $(".online-right .right-tab .tab-one").click(function() {
            console.log($(this))
            // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
            $(this).addClass("right-tab-active").siblings().removeClass("right-tab-active");
            // parent 父元素 next 下一个兄弟节点  children 子节点
            $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
        });
    },
}
$(document).ready(function () {
    online_ops.emojInit();
    online_ops.tabSwitch();
});
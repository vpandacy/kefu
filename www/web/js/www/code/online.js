;
var online_ops = {
    // 表情初始化
    emojInit:()=> {
        sdEditorEmoj.Init(emojiconfig);
        sdEditorEmoj.setEmoji({type: 'div', id: "content"});
    },
    // 菜單TAB切換
    tabSwitch:()=> {
        $(".online-right .right-tab .tab-one").click(function() {
            // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
            $(this).addClass("right-tab-active").siblings().removeClass("right-tab-active");
            // parent 父元素 next 下一个兄弟节点  children 子节点
            $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
        });
    },
    // 监听ws关闭
    wsClose:()=> {
        // 服务端的close_guest关闭客服 则触发
        window.ws.addEventListener('message', event =>{
            var data = JSON.parse(event.data);
            switch (data.cmd) {
                case 'close_guest' :
                    $('.chat-close').show();
                    // 打开留言
                    online_ops.messageOpen();
                    // 提交留言
                    online_ops.messageSubmit();
                    // 开始新对话
                    online_ops.messageInit();
                    break;
            }});
    },
    messageOpen:()=> {
        $('.online_from_message').click(()=> {
            $('#online-from').show();
        })
    },
    messageSubmit:() => {
        $('.from-button-message').click(()=>{
            let fromData = ['name','mobile','wechat','message']
            let param = {}
            fromData.forEach((value, index, array) => {
                param[value] = $("#online-from [name="+value+"]").val()
            })
            param['msn'] = JSON.parse(localStorage.getItem("serverInfo")).msn
            param['code'] = JSON.parse(localStorage.getItem("serverInfo")).code
            $.ajax({
                url:'/code/leave',
                type:'post',
                data:param,
                dataType: 'json',
                success: res => {
                    res.code != 200 ?  $.message({message:res.msg, type:'error'}) : $.message('提交成功');
                }
            })
        });
    },
    messageInit:()=> {
        $('.online_new_message').click(()=> {
            $('#online-from').hide();
            $('.chat-close').hide();
            window.chat_ops.init();
        })
    },
}
$(document).ready(function () {
    online_ops.emojInit();
    online_ops.tabSwitch();
    online_ops.wsClose();
});
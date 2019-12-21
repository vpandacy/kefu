;
var online_logic = {
    // 菜單TAB切換
    logic:function (){
        $(".online-right .right-tab .tab-one").click(function() {
            // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
            $(this).addClass("right-tab-active").siblings().removeClass("right-tab-active");
            // parent 父元素 next 下一个兄弟节点  children 子节点
            $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
            $('.online_new_message').click(()=> {
                $('#online-from').hide();
                $('.chat-close').hide();
                chat_ops_min.init();
            });
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
            $('.online_from_message').click(()=> {
                $('#online-from').show();
            });
        });
    },
}
var ws_config = new socket({
    input:'#content',
    emoji:'content',
    submit:'.submit-button',
    system:'.content-tip span',
    handle: function (data) {
        switch (data.cmd) {
            case 'ws_connect'||'hello':
                $('.ws_flag').text('正在连接客服...')
                break;
            case 'assign_kf'||'change_kf'||'reply' || 'system':
                $('.ws_flag').text('连接成功')
                break;
            case 'close_guest':
                // 主动关闭聊天.
                $('.chat-close').show();
                $('.ws_flag').text('连接关闭')
                break;
            default:
                $('.ws_flag').text('连接成功')
                break;
        }
    },
    renderSystemMessage: function (msg) {
        return  [
            '<div class="tip-div">',
            '   <span class="message-tip">',msg,'</span>',
            '</div>'
        ].join('');
    }
})
$(document).ready(function(){
    online_logic.logic();
    ws_config.init();
});
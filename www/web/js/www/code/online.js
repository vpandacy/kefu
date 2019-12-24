;
var online_logic = {
    // 菜單TAB切換
    logic:function (){
        $(".online-right .right-tab .tab-one").click(function() {
            // addClass 新增样式 siblings 返回带有switch-action 的元素 并移除switch-action
            $(this).addClass("right-tab-active").siblings().removeClass("right-tab-active");
            // parent 父元素 next 下一个兄弟节点  children 子节点
            $(this).parent().next().children().eq($(this).index()).show().siblings().hide();
        });

        $('.online_new_message').click(function(){
            $('#online-from').hide();
            $('.chat-close').hide();
            ws_config.init();
        });

        // 转留言.
        $('.online_from_message').click(function(){
            $('#online-from').show();
            ws_config.close();
            $('.chat-close').hide();
        });

        // 转留言.留言都要关闭ws链接.
        $('.leave-message span').on('click',function () {
            $('#online-from').show();
            $('.chat-close').hide();
            $('.overflow-message').hide();
            ws_config.close();
        });

        $('.from-button-message').click(function() {
            var fromData = ['name','mobile','wechat','message']
            var param = {};
            let name = $("#online-from [name='name']").val();
            let mobile = $("#online-from [name='mobile']").val()
            if(!name){
                return $.message({message:'请填写姓名', type:'error'});
            }
            if(!mobile || mobile.length != 11){
                return $.message({message:'请输入正确的手机号', type:'error'});
            }
            fromData.forEach(function(value, index, array){
                param[value] = $("#online-from [name="+value+"]").val();
            });
            var config = JSON.parse($('[name="params"]').val());
            param['msn'] = config.msn;
            param['code'] = config.code;

            $.ajax({
                url:'/code/leave',
                type:'post',
                data:param,
                dataType: 'json',
                success: function(res) {
                    if(res.code == 200) {
                        $('#online-from').hide();
                        $('.chat-close').show();
                    }
                    res.code != 200 ?  $.message({message:res.msg, type:'error'}) : $.message('提交成功');
                }
            })
        });
    },
    GetRequest: function () {
        if (typeof urlStr == "undefined") {
            var url = decodeURI(location.search); //获取url中"?"符后的字符串
        } else {
            var url = "?" + urlStr.split("?")[1];
        }
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for (var i = 0; i < strs.length; i++) {
                theRequest[strs[i].split("=")[0]] = decodeURI(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    }
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
                $('.ws_flag').text('连接成功');
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
    var getHistory = online_logic.GetRequest();
    Number(getHistory.isHistory) != 0 ? $('.line').hide():'';
    ws_config.init();
});
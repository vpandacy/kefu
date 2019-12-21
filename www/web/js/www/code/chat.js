;
var chat_logic = {
    logic: function() {
        /**
         * 控制右下角聊天切换状态
         */
        $('.show-hide').css({display:'none'});
        $('.show-hide-min').click(function () {
            $('.show-hide-min').css({display:'none'});
            $('.show-hide').css({display:'block'});
        });
        $('.show-hide-max').click(function () {
            $('.show-hide-min').css({display:'block'});
            $('.show-hide').css({display:'none'});
        });
        /**
         * 打开新窗口聊天页面
         */
        $('.icon-_DYGYxinyemiandakai').click(function () {
            // 这里要动态生成一下.
            var data = JSON.parse( $(".hidden_wrapper input[name=params]").val() );
            // 主动关闭聊天框.
            ws_config.autoClose();
            window.open(data['tab_url'], 'newindow', 'height=610,width=810,top=150,left=1000,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no');
            $('.show-hide-min').css({display:'block'});
            $('.show-hide').css({display:'none'});
        });

        $('.icon-jianqie').click(function () {
            $('.online-cover').show();
            $('.capture-dialog').show();
        });

        $('.icon-guanbi').click(function () {
            $('.online-cover').hide();
            $('.capture-dialog').hide();
        });

        // 新会话
        $('.online_new_message').click(function(){
            ws_config.init();
            $('#online-from').hide();
            $('.chat-close').hide();
            $('.content-tip .line').text('显示上次聊天记录');
        });

        // 留言. 留言都要关闭ws链接.
        $('.online_from_message').click(function(){
            $('#online-from').show();
            $('.chat-close').hide();
            ws_config.close();
        });

        // 转留言.留言都要关闭ws链接.
        $('.leave-message span').on('click',function () {
            $('#online-from').show();
            $('.chat-close').hide();
            ws_config.close();
        });

        $('.from-button-message').click(function(){
            var fromData = ['name','mobile','wechat','message'];
            var param = {};

            fromData.forEach(function (value, index, array) {
                param[value] = $("#online-from [name="+value+"]").val()
            });

            var config = JSON.parse($('[name="params"]').val());
            param['msn'] = config.msn;
            param['code'] = config.code;

            $.ajax({
                url:'/code/leave',
                type:'post',
                data:param,
                dataType: 'json',
                success: function (res) {
                    if(res.code == 200) {
                        $('.from-button-message').hide();
                    }
                    res.code != 200 ?  $.message({message:res.msg, type:'error'}) : $.message('提交成功');
                }
            });
        });
    }
}
var ws_config = new socket({
    input:'#content',
    emoji:'content',
    submit:'.submit-button',
    system:'.content-tip .line',
    handle: function (data) {
        switch (data.cmd) {
            case 'ws_connect'||'hello':
                $('.ws_flag').text('正在连接客服...')
                break;
            case 'assign_kf'||'change_kf'||'reply' || 'system':
                $('.ws_flag').text('连接成功')
                break;
            case 'close_guest':
                $('.chat-close').show();
                $('.ws_flag').text('连接关闭')
                break;
            default:
                $('.ws_flag').text('连接成功')
                break;
        }
    }
})
$(document).ready(function(){
    chat_logic.logic();
    ws_config.init();
});
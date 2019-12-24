;
var mobile_logic = {
    logic: function () {
        $('.icon-zaixianzixun').click(function () {
            $('.waponline-max').removeClass('dis_none');
            $('.icon-zaixianzixun').addClass('dis_none');
        });
        $('.icon-zuojiantou').click(function () {
            $('.waponline-max').addClass('dis_none');
            $('.icon-zaixianzixun').removeClass('dis_none');
        })

        // 点击新对话.
        $('.online_new_message').on('click', function () {
            ws_config.init();
            $('.chat-close').hide();
        });

        // 点击留言
        $('.online_from_message').on('click', function () {
            $('#online-from').show();
            $('.chat-close').hide();
            $('.overflow-message').hide();
            ws_config.close();
        });

        // 去留言.
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
                success: function(res) {
                    if(res.code == 200) {
                        $('#online-from').hide();
                        $('.chat-close').show();
                    }
                    alert(res.msg);
                }
            })
        });
    }
}
var ws_config = new socket({
    input:'#content',
    emoji:'content',
    submit:'.submit-button',
    system:'.message span',
    handle: function (data) {
        switch (data.cmd) {
            case 'ws_connect'||'hello':
                $('.ws_flag').text('正在连接客服...');
                break;
            case 'assign_kf'||'change_kf'||'reply' || 'system':
                $('.ws_flag').text('连接成功');
                break;
            case 'close_guest':
                // 主动关闭聊天.
                $('.ws_flag').text('连接中止');
                break;
            default:
                $('.ws_flag').text('连接成功');
                break;
        }
    },
    renderSystemMessage: function (msg) {
        return  [
            '<div class="tip">',
            '   <span>',msg,'</span>',
            '</div>'
        ].join('');
    }
})
$(document).ready(function () {
    mobile_logic.logic();
    ws_config.init();
});

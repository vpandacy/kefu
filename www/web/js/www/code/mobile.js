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
                $('.ws_flag').text('正在连接客服...')
                break;
            case 'assign_kf'||'change_kf'||'reply' || 'system':
                $('.ws_flag').text('连接成功')
                break;
            case 'close_guest':
                // 主动关闭聊天.
                ws_config.autoClose();
                $('.ws_flag').text('连接中止')
                break;
            default:
                $('.ws_flag').text('连接成功')
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

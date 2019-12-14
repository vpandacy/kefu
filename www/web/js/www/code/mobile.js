;
$(document).ready(function () {
    /**
     * 表情
     * */
    sdEditorEmoj.Init(emojiconfig);
    sdEditorEmoj.setEmoji({type: 'input', id: "content"});
    $('.icon-zaixianzixun').click(function () {
        $('.waponline-max').removeClass('dis_none');
        $('.icon-zaixianzixun').addClass('dis_none');
    });

    $('.icon-zuojiantou').click(function () {
        $('.waponline-max').addClass('dis_none');
        $('.icon-zaixianzixun').removeClass('dis_none');
    })
});

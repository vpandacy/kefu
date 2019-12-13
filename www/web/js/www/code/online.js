$(document).ready(function() {
    /**
     * 表情
     * */
    sdEditorEmoj.Init(emojiconfig);
    sdEditorEmoj.setEmoji({type: 'div', id: "content"});
    /**
     * 截图初始化
     */
    $().ready(function(){
        $('#moreparams').hide();

        $('#captureselectSize').click( function(){
            var autoFlag = $("#captureselectSize").attr("checked")=="checked" ? 1 : 0;
            if(autoFlag == 1){
                $('#moreparams').show();
            }
            else{
                $('#moreparams').hide();
            }
        });
        $('#getimagefromclipboard').click( function(){
            $('#posdetail').hide();
        });
        $('#showprewindow').click( function(){
            $('#posdetail').hide();
        });
        $('#fullscreen').click( function(){
            $('#posdetail').hide();
        });
        $('#specificarea').click( function(){
            $('#posdetail').show();
        });

        $('#showprewindow').click();
        $('#autoupload').click();
        $('#btnUpload').hide();
        Init();
    })
})
;

var index_ops = {
    init:function(){
        this.eventBind();
        this.getWord();
    },
    eventBind:function(){
        var that  = this;
        var chat = new Chat();
        chat.init();

        $(".refresh_word").click( function(){
            that.getWord();
        } );

        $(".exe-header-info-kfname").click(function(){
            window.location.href = window.location.href;
        });

    },
    getWord:function(){
        $.ajax({
            url:cs_common_ops.buildKFCSurl("/util/get-word"),
            dataType:'json',
            success:function( res ){
                var data = res.data;
                var content = '';
                for (var idx in data){
                    var tmp_data = data[ idx ];
                    var tmp_title = tmp_data['title'];
                    var tmp_word = tmp_data['words'];
                    if( !tmp_title ){
                        tmp_title = tmp_word.substr(0,10);
                    }
                    var tmp_str = '<div class="content-select">\n' +
                        '<i class="iconfont icon-wenjian"></i>\n' +
                        '<span title="'+tmp_word+'">'+tmp_title+'</span>' +
                        '</div>';
                    content = content + tmp_str;
                }
                $(".words-content").html( content );
            }
        });
    }
};

$(document).ready(function () {
    index_ops.init();
});
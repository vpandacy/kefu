;

var index_ops = {
    init:function(){
        this.eventBind();
    },
    eventBind:function(){
        var chat = new Chat();
        chat.init();
    }
};

$(document).ready(function () {
    index_ops.init();
});
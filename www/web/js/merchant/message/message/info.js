;
var message_info_ops = {
    init:function(){
        this.tab_index = 0;
        this.eventBind();
    },
    eventBind:function(){
        var that = this;

        $("#pop_layer").off("click",".chat_log").on("click",".chat_log", function(){
            $("#pop_layer .chat_log").addClass("layui-btn-primary").removeClass("active");
            $(this).removeClass("layui-btn-primary").addClass("active");
            that.tab_index = 0;
            $("#pop_layer .layui-tab ul.layui-tab-title li").removeClass("layui-this");
            $( $("#pop_layer .layui-tab ul.layui-tab-title li").get(0) ).addClass("layui-this");
            that.getTabData();
        });


        $("#pop_layer .pop_right").css( "min-height",$("#pop_layer").height() + "px" );


        $("body").off("click","#pop_layer .prev").on("click","#pop_layer .prev",function(){
            that.preAndNext( -1 );
        });

        $("body").off("click","#pop_layer .next").on("click","#pop_layer .next",function(){
            that.preAndNext( 1 );
        });

        $("#pop_layer .close").click( function(){
            $(".layui-table-body tbody tr").removeClass("layui-bg-green");
            layer.closeAll();
        });

        $("#pop_layer .layui-tab ul li").click(function( ){
            var index= $("#pop_layer .layui-tab ul li") .index($(this));
            that.tab_index = index;
            that.getTabData();
        });
        //默认查看第一个
        $("#pop_layer .chat_log").get(0).click();
    },
    getTabData:function(){
        var index = this.tab_index;
        var url = merchant_common_ops.buildMerchantUrl("/message/message/chat");
        if( index == 1 ){
            url = merchant_common_ops.buildMerchantUrl("/message/message/log");
        }else if( index == 2 ){
            url = merchant_common_ops.buildMerchantUrl("/message/message/trace");
        }
        $.ajax({
            url:url,
            data:{
                id:$("#pop_layer .pop_left .active").data("id")
            },
            dataType:'json',
            success:function( res ){
                $("#pop_layer .pop_right .layui-tab-content").html( res.data.content );
            }
        });
    },
    preAndNext:function( step ){
        var target_index = message_index_ops.tr_index + step;
        if( target_index < 0 || target_index >= message_index_ops.tr_count ){
            return;
        }
        message_index_ops.tr_index = target_index;
        $(".layui-table-body tbody tr").removeClass("layui-bg-green");
        $( $(".layui-table-body tbody tr").get(target_index) ).addClass("layui-bg-green");
        var target_uuid = $( $(".layui-table-body .info").get( target_index ) ).data("uuid");
        if( target_uuid == message_index_ops.tr_uuid ){
            return;
        }

        $(".layui-table-body .info").get( target_index ).click();
    }
};

$(document).ready(function(){
    message_info_ops.init();
});
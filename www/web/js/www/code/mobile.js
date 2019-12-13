;
var mobile_ops ={
    init: function () {
        this.eventBind();
    },
    eventBind: function () {
        $('.icon-zaixianzixun').click(function () {
            $('.waponline-max').removeClass('dis_none')
            $('.icon-zaixianzixun').addClass('dis_none')
        });
        
        $('.icon-zuojiantou').click(function () {
            $('.waponline-max').addClass('dis_none')
            $('.icon-zaixianzixun').removeClass('dis_none')
        })
    }
};

$(document).ready(function () {
    mobile_ops.init();
});

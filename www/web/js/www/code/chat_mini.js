$(function () {
    $('.show-hide').css({display:'none'})
    $('.show-hide-min').click(function () {
        $('.show-hide-min').css({display:'none'})
        $('.show-hide').css({display:'block'})
    })
    $('.show-hide-max').click(function () {
        $('.show-hide-min').css({display:'block'})
        $('.show-hide').css({display:'none'})
    })

    $('.icon-fenxiang').click(function () {
        window.open('http://www.kefu.dev.hsh568.cn//code/online', 'newindow', 'height=610,width=810,top=150,left=550,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no')
        $('.show-hide-min').css({display:'block'})
        $('.show-hide').css({display:'none'})
    })
})

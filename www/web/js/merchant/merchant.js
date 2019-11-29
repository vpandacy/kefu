/**
 * 菜单栏JS
 */
$('.menu-title a').click(function () {
   $(this).children('.iconfont').addClass('li_active');
});
$('.menu-title a').each(function () {
   var icon = $(this).children('.iconfont');
   var dataUrl = $(this).attr('data-url');
   var url = document.URL;
   url.indexOf(dataUrl) > -1 ? icon.addClass('li_active') : icon.removeClass('li_active');
});
var resizeDiv = document.getElementById('left_menu');
$('.menu_bottom').click(function () {
   $('.menu-show-hide').each(function () {
      $(this).toggle();
   });
});
var lockSize = function () {
   resizeDiv.offsetWidth > 150 ? $('.menu-show').show().addClass('bounceInLeft animated'):'';
}
var closeSize = function () {
   resizeDiv.offsetWidth < 180 ? $('.menu-show').hide() : '';
}
function menuLock() {
   EleResize.off(resizeDiv, closeSize);
   $('.left_menu').width('190px');
   $('#merchant .chant_all .right_merchant .right_content').css('margin-left','190px');
   EleResize.on(resizeDiv,lockSize);
}
function menuClose() {
   EleResize.off(resizeDiv, lockSize);
   $('.left_menu').width('90px');
   $('#merchant .chant_all .right_merchant .right_content').css('margin-left','90px');
   EleResize.on(resizeDiv,closeSize);
}
$('.menu-title a').mouseover(function () {
   $('.left_menu').width() > 95 ?   $(this).children('.menu-tooltip').hide() : $(this).children('.menu-tooltip').show();
   $(this).children('.menu-tooltip').addClass('fadeIn animated');
})
$('.menu-title a').mouseout(function () {
   $(this).children('.menu-tooltip').hide();
})



var $submenu = $('.submenu');
var $mainmenu = $('.mainmenu');
$submenu.hide();
$submenu.first().delay(400).slideDown(700);
$submenu.on('click','li', function() {
   $submenu.siblings().find('li').removeClass('chosen');
   $(this).addClass('chosen');
});
$mainmenu.on('click', 'li', function() {
   $(this).next('.submenu').slideToggle().siblings('.submenu').slideUp();
});
$mainmenu.children('li:last-child').on('click', function() {
   $mainmenu.fadeOut().delay(500).fadeIn();
});
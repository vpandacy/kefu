<?php
use \common\services\GlobalUrlService;
$url = GlobalUrlService::buildWwwUrl('/code/chat');
?>


var iframe_url = "<?=$url;?>";
window.onload = function () {
    var dynamicLoading = {
        online: function () {
            let ifream = "<iframe src='<?=$url;?>' scrolling='no' frameborder='0' height='450' width='400' style='min-width: 1px;min-height: 1px;position: absolute;bottom: 0;right: 0;'></iframe>";
            document.getElementsByTagName('body')[0].insertAdjacentHTML("beforeEnd", ifream);
        },
    };
    dynamicLoading.online();
};
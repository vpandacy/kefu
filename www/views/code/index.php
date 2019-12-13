<?php if(!isset($is_mobile)):?>
    window.onload = function () {
        var dynamicLoading = {
            online: function () {
                let ifream = "<iframe src='<?=$url;?>' scrolling='no' frameborder='0' style='min-width: 1px; width:100%; height: 100%;min-height: 1px;position: absolute;bottom: 0;right: 0;top: 0;left: 0;'></iframe>";
                document.getElementsByTagName('body')[0].insertAdjacentHTML("beforeEnd", ifream);
            },
        };

        dynamicLoading.online();
    };
<?php else:?>
window.onload = function () {
    var dynamicLoading = {
        online: function () {
            let ifream = "<iframe src='<?=$url;?>' scrolling='no' frameborder='0' height='450' width='400' style='min-width: 1px;min-height: 1px;position: absolute;bottom: 0;right: 0;'></iframe>";
            document.getElementsByTagName('body')[0].insertAdjacentHTML("beforeEnd", ifream);
        },
    };
    dynamicLoading.online();
};
<?php endif;?>
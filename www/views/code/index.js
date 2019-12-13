<<<<<<< ec9f3a243f6563b9f865f9c480f8dba2d72ed697:www/views/code/index.php
<?php if($is_mobile):?>
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
=======
;
>>>>>>> guowei -- 把代维的扫码做一下:www/views/code/index.js
window.onload = function () {
    var dynamicLoading = {
        online: function () {
            let ifream = "<iframe src='<?=$url;?>' scrolling='no' frameborder='0' height='450' width='400' style='min-width: 1px;min-height: 1px;position: absolute;bottom: 0;right: 0;'></iframe>";
            document.getElementsByTagName('body')[0].insertAdjacentHTML("beforeEnd", ifream);
        },
        getUUID:function(){
            //需要设置uuid存储在浏览器中，时间最好不要过期
            return "<?=$uuid;?>";
        }
    };
    dynamicLoading.online();
};
<?php endif;?>
<?php if($is_mobile):?>
!function(win, doc) {
    "use strict";
    var dynamicLoading = {
        online: function () {
            var iframe = document.createElement('iframe');

            iframe.src = '<?=$url?>';

            function load() {
                // 传参过去.然后根据参数来获取就可以了.
                iframe.contentWindow.postMessage(JSON.stringify({
                    href : location.href,
                    rf: document.referrer,
                    title: document.title
                }), iframe.src);
            }

            if (iframe.attachEvent) {
                iframe.attachEvent('onload', load);
            } else {
                iframe.onload  = load;
            }

            iframe.setAttribute('scrolling','no');

            iframe.style['min-height'] = '100%';
            iframe.style['height'] = '100%';
            iframe.style['min-width'] = '100%';
            iframe.style['width'] = '100%';
            iframe.style['z-index'] = '9999';
            iframe.style['position'] = 'fixed';
            iframe.style['top'] = '0';
            iframe.style['bottom'] = '0';
            iframe.style['right'] = '0';
            iframe.style['left'] = '0';
            iframe.style['border'] = 'none';

            var body = document.getElementsByTagName('body')[0];

            body.appendChild(iframe);
        }
    };
    dynamicLoading.online();
}(window, document);
<?php else:?>
!function(win, doc) {
    "use strict";
    var dynamicLoading = {
        online: function () {
            var iframe = document.createElement('iframe');

            iframe.src = '<?=$url?>';

            function load() {
                // 传参过去.然后根据参数来获取就可以了.
                iframe.contentWindow.postMessage(JSON.stringify({
                    href : location.href,
                    rf: document.referrer,
                    title: document.title
                }), iframe.src);
            }

            if (iframe.attachEvent) {
                iframe.attachEvent('onload', load);
            } else {
                iframe.onload  = load;
            }

            iframe.setAttribute('scrolling','no');

            iframe.style['min-height'] = '1px';
            iframe.style['height'] = '650px';
            iframe.style['min-width'] = '1px';
            iframe.style['width'] = '400px';
            iframe.style['border'] = 'none';
            iframe.style['position'] = 'fixed';
            iframe.style['bottom'] = '0';
            iframe.style['right'] = '0';
            iframe.style['z-index'] = '9999';
            iframe.style['border'] = 'none';
            iframe.style['overflow']= 'hidden';

            var body = document.getElementsByTagName('body')[0];

            body.appendChild(iframe);
        }
    };
    dynamicLoading.online();
}(window, document);
<?php endif;?>
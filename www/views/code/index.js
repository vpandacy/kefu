<?php if($is_mobile):?>
    window.onload = function () {
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
                iframe.style['position'] = 'absolute';
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
    };
<?php else:?>
window.onload = function () {
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
            iframe.style['height'] = '450px';
            iframe.style['min-width'] = '1px';
            iframe.style['width'] = '400px';
            iframe.style['position'] = 'absolute';
            iframe.style['bottom'] = '0';
            iframe.style['right'] = '0';
            iframe.style['border'] = 'none';
            iframe.style['overflow']= 'hidden';

            var body = document.getElementsByTagName('body')[0];

            body.appendChild(iframe);
        }
    };

    dynamicLoading.online();
};
<?php endif;?>
<?php
use common\services\GlobalUrlService;
/**
 * @var \yii\web\View $this
 */
?>
<script>
    (function() {
        var _hshcode = document.createElement("script"),
            s = document.getElementsByTagName("script")[0];

        _hshcode.src = "<?=GlobalUrlService::buildWwwUrl('/'.$this->params['merchant']['sn'] . '/code/index')?>";
        s.parentNode.insertBefore(_hshcode, s);
    })();
</script>
<?php
use common\services\GlobalUrlService;
/**
 * @var \yii\web\View $this
 */

$url = $group_sn
    ? GlobalUrlService::buildWwwUrl('/'.$this->params['merchant']['sn'] . '/code/index',['code'=>$group_sn])
    : GlobalUrlService::buildWwwUrl('/'.$this->params['merchant']['sn'] . '/code/index');
?>
<script>
    (function() {
        var _hshcode = document.createElement("script"),
            s = document.getElementsByTagName("script")[0];

        _hshcode.src = "<?=$url?>";
        s.parentNode.insertBefore(_hshcode, s);
    })();
</script>
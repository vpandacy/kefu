<?php
use common\services\GlobalUrlService;
/**
 * @var \yii\web\View $this
 */

$url = $group_sn
    ? GlobalUrlService::buildKFUrl('/'.$this->params['merchant']['sn'] . '/code/index',['code'=>$group_sn])
    : GlobalUrlService::buildKFUrl('/'.$this->params['merchant']['sn'] . '/code/index');
?>
<script>
    (function() {
        var _ht_kf_code = document.createElement("script"),
            s = document.getElementsByTagName("script")[0];

        _ht_kf_code.src = "<?=$url?>";
        s.parentNode.insertBefore(_ht_kf_code, s);
    })();
</script>
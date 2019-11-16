<?php
use \common\services\GlobalUrlService;
$url = GlobalUrlService::buildWwwUrl("/code/chat");
?>
;

var iframe_url = "<?=$url;?>";
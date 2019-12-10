<?php
use common\services\GlobalUrlService;
use \uc\services\UCUrlService;
?>
<div style="display: none;" class="hidden_val_wrap">
    <input name="domain_app" value="<?=GlobalUrlService::buildUCUrl("");?>"/>
    <input name="domain_uc" value="<?=UCUrlService::buildUCUrl("");?>"/>
</div>
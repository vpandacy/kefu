<?php
use common\services\GlobalUrlService;
use \uc\services\UCUrlService;
?>
<div style="display: none;" class="hidden_val_wrap">
    <input name="domain_app" value="<?=GlobalUrlService::buildUCUrl("");?>"/>
    <input name="domain_uc" value="<?=UCUrlService::buildUCUrl("");?>"/>
    <input name="login_status" value="<?=isset($this->params['current_user'])?1:0;?>"/>
</div>
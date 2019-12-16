<?php
use common\services\GlobalUrlService;

?>
<div style="display: none;" class="hidden_val_wrap">
    <input type="hidden" name="domain_app" value="<?=GlobalUrlService::buildKFCSUrl("");?>"/>
    <input type="hidden" name="domain_uc" value="<?=GlobalUrlService::buildUCUrl("");?>"/>
    <input type="hidden" name="login_status" value="<?=isset($this->params['current_user'])?1:0;?>"/>
</div>

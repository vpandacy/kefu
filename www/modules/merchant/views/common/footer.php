<?php
use common\services\GlobalUrlService;

$cdn_config = Yii::$app->params["cdn"];
unset( $cdn_config['qiniu_config'] );
?>
<div style="display: none;" class="hidden_val_wrap">
    <input type="hidden" name="domain_app" value="<?=GlobalUrlService::buildKFMerchantUrl("");?>"/>
    <input type="hidden" name="domain_uc" value="<?=GlobalUrlService::buildUCUrl("");?>"/>
    <input type="hidden" name="domain_cdn" value='<?=json_encode($cdn_config)?>'>
    <input type="hidden" name="login_status" value="<?=isset($this->params['current_user'])?1:0;?>"/>
</div>
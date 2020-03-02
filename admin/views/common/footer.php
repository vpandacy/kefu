<?php
$cdn_config = Yii::$app->params["cdn"];
unset( $cdn_config['qiniu_config'] );
?>
<div style="display: none;" class="hidden_val_wrap">
    <input name="domain" value="<?=Yii::$app->params["domains"]["admin"];?>"/>
    <input name="login_status" value="<?=isset($current_user)?1:0;?>"/>
    <input name="domain_cdn" value='<?=json_encode($cdn_config);?>'/>
</div>

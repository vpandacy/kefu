<?php
use common\services\GlobalUrlService;
use \uc\services\UcUrlService;
use \common\components\helper\UtilHelper;
$app_id = \Yii::$app->view->params['app_type'];
?>
<a href="<?=GlobalUrlService::buildNullUrl();?>" class="dropdown-toggle" data-toggle="dropdown">
    <img src="<?=  GlobalUrlService::buildPicStaticUrl('hsh', $current_user['avatar']); ?>" class="user-image" alt="User Image">
    <span class="hidden-xs"><?=UtilHelper::encode( $current_user["name"] );?></span>
</a>
<ul class="dropdown-menu">
    <li role="separator" class="divider"></li>
    <li>
        <a href="<?=UcUrlService::buildUCUrl("/user/logout",$app_id);?>">
            <i class="fa fa-sign-out" aria-hidden="true"></i>退出
        </a>
    </li>
</ul>
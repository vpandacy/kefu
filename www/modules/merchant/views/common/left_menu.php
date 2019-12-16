<?php
use common\services\GlobalUrlService;
/**
 * @var \yii\web\View $this
 */
?>
<!-- 左侧菜单栏 -->
<div class="left_menu" id="left_menu">
    <div class="menu-logo">
        <a>
            <img class="menu_min_logo dis_none" alt="商通" src="<?=GlobalUrlService::buildKFStaticUrl("/images/merchant/logo.png");?>">
            <img class="menu_max_logo " src="<?=GlobalUrlService::buildKFStaticUrl("/images/merchant/biglogo.png");?>">
        </a>
    </div>
    <div class="menu-version"><?=$this->params['merchant']['name']?></div>
    <div class="menu-title">
        <?php foreach($this->params['menus']['left_menu'] as $key => $menu):?>
            <a href="<?=GlobalUrlService::buildKFUrl('/' . $menu['url']);?>">
                <div class="menu-tooltip"><?=$menu['title']?></div>
                <i class="iconfont <?=$menu['icon']?>"></i>
                <div class="menu-show dis_none" style="display: block"><?=$menu['title']?></div>
            </a>
        <?php endforeach;?>
    </div>
</div>

<?php
use common\services\GlobalUrlService;
/**
 * @var \yii\web\View $this
 */
?>
<!-- 左侧菜单栏 -->
<div class="left_menu" id="left_menu">
    <div class="menu-logo">
        <a><img src="<?=GlobalUrlService::buildKFStaticUrl("/images/merchant/logo1.png");?>"></a>
    </div>
    <div class="menu-version"><?=$this->params['merchant']['name']?></div>
    <div class="menu-title">
        <?php foreach($this->params['menus']['left_menu'] as $key => $menu):?>
            <a href="<?=GlobalUrlService::buildKFUrl('/' . $menu['url']);?>">
                <div class="menu-tooltip"><?=$menu['title']?></div>
                <i class="iconfont <?=$menu['icon']?>"></i>
                <div class="menu-show dis_none"><?=$menu['title']?></div>
            </a>
        <?php endforeach;?>
    </div>

    <div class="menu_bottom">
        <div class="menu-show-hide" onclick="menuLock()">
            <a>
                <i class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-1" ></i>
            </a>
        </div>
        <div class="menu-show-hide dis_none" onclick="menuClose()">
            <a>
                <i class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-1" ></i>
            </a>
        </div>
    </div>
</div>

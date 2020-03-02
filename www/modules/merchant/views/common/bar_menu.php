<?php
use common\services\GlobalUrlService;
/**
 * @var \yii\web\View $this
 * @var string $bar_menu        当前的bar_menu.详细可看MenuService下的getBarMenu
 * @var string $current_menu    当前选中的key.例如sub_user
 */
?>
<div class="staff_tab">
    <?php foreach($this->params['menus']['bar_menu'][$bar_menu] as $key=> $menu):?>
        <div class="tab_list <?=$key == $current_menu ? 'tab_active' : ''?> ">
            <a href="<?=GlobalUrlService::buildKFUrl('/' . $menu['url']);?>"><?=$menu['title']?></a>
        </div>
    <?php endforeach;?>
</div>
<?php

use common\services\GlobalUrlService;

$menus = \Yii::$app->view->params['menus']
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <?php if ($menus): ?>
                <?php foreach ($menus as $_key => $_item): ?>
                    <?php if (!isset($_item['hidden']) || !$_item['hidden']): ?>
                    <li class="treeview    menu_<?= $_key; ?>">
                        <a href="<?= GlobalUrlService::buildNullUrl(); ?>">
                            <i class="fa fa-<?= $_item['icon']; ?>"></i> <span><?= $_item["title"]; ?></span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu">
                            <?php foreach ($_item['sub'] as $_sub_menu): ?>

                                <?php if (!isset($_sub_menu['hidden']) || !$_sub_menu['hidden']): ?>
                                <li>
                                    <?php if(in_array($_key,\common\services\AppMenuService::$uc_keys )):?>
                                        <a href="<?= GlobalUrlService::buildKFAdminUrl('/uc'.$_sub_menu['url']); ?>">
                                            <i class="fa fa-circle-o"></i><?= $_sub_menu['title']; ?>
                                        </a>
                                    <?php else:?>
                                    <a href="<?= GlobalUrlService::buildKFAdminUrl($_sub_menu['url']); ?>">
                                        <i class="fa fa-circle-o"></i><?= $_sub_menu['title']; ?>
                                    </a>
                                    <?php endif;?>
                                </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </section>
</aside>
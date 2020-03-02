<?php

use admin\assets\Asset;
use \common\components\helper\UtilHelper;
use \common\services\GlobalUrlService;
$sysconfig = Yii::$app->params['sysconfig']['admin'];
Asset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= \Yii::$app->view->renderFile("@admin/views/common/header.php"); ?>
    <title><?= UtilHelper::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<!--
skin-purple-light -- 紫色
skin-blue - 蓝色
公司主色 #5dc2d0
sidebar-collapse
-->

<body class="sidebar-mini  skin-blue">
<?php $this->beginBody() ?>
<div class="wrapper">
    <!-- 头部内容 -->
    <header class="main-header">
        <!-- Logo -->
        <a href="<?= GlobalUrlService::buildNullUrl(); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b><?=$sysconfig['menu_title'];?></b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b><?=$sysconfig['menu_title'];?></b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="<?= GlobalUrlService::buildNullUrl(); ?>" class="sidebar-toggle" data-toggle="push-menu"
               role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu hidden">
                        <a href="<?= GlobalUrlService::buildNullUrl(); ?>">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                    </li>
                    <?= \Yii::$app->view->renderFile("@admin/views/common/user_info.php",[
                            "current_user" => $this->params['current_user']
                    ]); ?>
                </ul>
            </div>
        </nav>
    </header>
    <!--左侧导航栏-->
    <?= \Yii::$app->view->renderFile("@admin/views/common/left_menu.php"); ?>
    <!-- 主要内容 -->
    <div class="content-wrapper">
        <div class="content-tabs hidden">
            <button class="roll-nav roll-left tabLeft" onclick="scrollTabLeft()">
                <i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs menuTabs tab-ui-menu" id="tab-menu">
                <div class="page-tabs-content" style="margin-left: 0px;">
                </div>
            </nav>
            <button class="roll-nav roll-right tabRight" onclick="scrollTabRight()">
                <i class="fa fa-forward" style="margin-left: 3px;"></i>
            </button>
        </div>
        <div class="content-iframe hidden" style="background-color: #ffffff; ">
            <div class="tab-content " id="tab-content">

            </div>
        </div>
        <?= $content; ?>
    </div>
    <!-- 底部 -->
    <footer class="main-footer text-center">
        <strong>Copyright &copy; 2019 <a href="<?= GlobalUrlService::buildNullUrl(); ?>"><?=$sysconfig['footer'];?></a>.</strong> All
        rights reserved.
    </footer>
</div>
<?= \Yii::$app->view->renderFile("@admin/views/common/footer.php",["current_user" => $this->params['current_user']]); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<?php

use admin\assets\Asset;
use \common\components\helper\UtilHelper;

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
    <!-- 主要内容 -->
    <div class="content-wrapper" style="margin-left: 0px;">
        <?= $content; ?>
    </div>
</div>
<?= \Yii::$app->view->renderFile("@admin/views/common/footer.php",["current_user" => $this->params['current_user']]); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

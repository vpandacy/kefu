<?php
use common\services\GlobalUrlService;
use www\assets\CsAsset;

/**
 * @var \yii\web\View $this
 */
CsAsset::register($this);
?>
<?=$this->beginPage()?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>exe端聊天</title>
    <?=$this->head() ?>
</head>
<?=$this->beginBody()?>
<body onclick="tab.listHide(true)">
<?=$content?>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
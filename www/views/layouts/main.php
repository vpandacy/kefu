<?php
use www\assets\AppAsset;
/**
 * @var \yii\web\View $this
 */

AppAsset::register($this);
?>
<!DOCTYPE html>
<?=$this->beginPage()?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <?=$this->head()?>
</head>
<body>
<?=$this->beginBody()?>
<?=$content?>
<?=$this->endBody()?>
</body>
</html>
<?=$this->endPage()?>

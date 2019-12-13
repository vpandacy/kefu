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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?=$this->head()?>
</head>
<body>
<?=$this->beginBody()?>
<?=$content?>
<?=$this->endBody()?>
</body>
</html>
<?=$this->endPage()?>

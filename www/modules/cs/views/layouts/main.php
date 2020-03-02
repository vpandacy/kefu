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
    <title><?=$this->title?></title>
    <?=$this->head() ?>
</head>
<?=$this->beginBody()?>
<body>
    <?=$content?>

    <?=$this->renderFile('@www/modules/cs/views/common/footer.php')?>
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
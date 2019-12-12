<?php
use common\services\GlobalUrlService;

/**
 * @var \yii\web\View $this
 */
?>
<?=$this->beginPage()?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>登录</title>
    <link href="<?=GlobalUrlService::buildUcStaticUrl("/css/user/login.css");?>" rel="stylesheet">
    <link href="<?=GlobalUrlService::buildUcStaticUrl("/css/user/typeface/typeface.css");?>" rel="stylesheet">
</head>
<?=$this->beginBody()?>
<body>
<?=$content?>
<?=$this->renderFile('@uc/views/common/footer.php')?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
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
    <link href="<?=GlobalUrlService::buildWwwStaticUrl("/css/merchant/user/login.css");?>" rel="stylesheet">

</head>
<?=$this->beginBody()?>
<body style="background: url('<?=GlobalUrlService::buildWwwStaticUrl("/images/merchant/user/bg.jpg");?>') no-repeat 0 0; background-size: cover">
<?=$content?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
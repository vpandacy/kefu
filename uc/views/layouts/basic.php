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

</head>
<?=$this->beginBody()?>
<body style="background: url('<?=GlobalUrlService::buildUcStaticUrl("/images/user/bg.jpg");?>') no-repeat 0 0; background-size: cover">
<?=$content?>

<?php $this->endBody() ?>
<script>
var application_setting = {
    'app_name': '<?=$this->params['app_name']?>',
    'domains' : <?=json_encode(Yii::$app->params['domains'])?>
}
</script>
</body>
</html>
<?php $this->endPage() ?>
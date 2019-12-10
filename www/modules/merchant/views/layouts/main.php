<?php
use www\assets\MerchantAsset;
use common\services\GlobalUrlService;

/**
 * @var \yii\web\View $this
 */

MerchantAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" href="<?=GlobalUrlService::buildWwwStaticUrl("/images/favicon.ico");?>" type="image/x-icon"/>
    <title><?=Yii::$app->params["company"]["title"];?> -- 商户后台</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div id="merchant">
        <div class="chant_all">

            <?=$this->renderFile('@www/modules/merchant/views/common/left_menu.php')?>

            <!-- 右侧菜单栏 -->
            <div class="right_merchant">
                <!-- 头部个人信息 -->
                <div class="right_top">
                    <!-- 这里通过js异步加载出来 -->
                </div>
                <!-- 内容区域 -->
                <div class="right_content">
                    <?= $content; ?>
                </div>
            </div>
        </div>
    </div>
<?=$this->renderFile('@www/modules/merchant/views/common/footer.php')?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<?php
use uc\assets\UcAsset;
use common\services\GlobalUrlService;
use uc\service\UcUrlService;

/**
 * @var \yii\web\View $this
 */

UcAsset::register($this);
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

        <?=$this->renderFile('@uc/views/common/left_menu.php')?>

        <!-- 右侧菜单栏 -->
        <div class="right_merchant">
            <!-- 头部个人信息 -->
            <div class="right_top">
                <a><i class="iconfont icon-quanjushezhi"></i></a>
                <a><i class="iconfont icon-xinxi-copy"></i></a>
                <a><i class="iconfont icon-tongzhi"></i></a>
                <a href="<?=UcUrlService::buildUcUrl('/staff/edit',['staff_id'=>$this->params['staff']['id']])?>" class="menu_info_link">
                    <img class="menu_info_img" src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$this->params['staff']['avatar']);?>">
                </a>
                <div class="menu_info_edit dis_none">
                    <div class="info_edit_one">
                        <div >
                            <img src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$this->params['staff']['avatar']);?>">
                        </div>
                        <div>
                            <div class="info_ms_two">
                                <label><?=$this->params['staff']['name']?></label>
                            </div>
                            <div class="info_ms_three">
                                <label><?=$this->params['staff']['mobile'] ? $this->params['staff']['mobile'] : '暂无手机号'?></label>
                            </div>
                        </div>
                        <div>
                            <a href="<?=UcUrlService::buildUcUrl('/staff/edit',['staff_id'=>$this->params['staff']['id']])?>">编辑</a>
                        </div>
                    </div>
                    <div class="info_edit_two backFFF logout" style="cursor: pointer" onclick="location.href=url_manager.buildUcUrl('/user/logout');">
                        <div>
                            <i class="iconfont icon-tuichu"></i>
                        </div>
                        <div>退出</div>
                    </div>
                </div>
            </div>
            <!-- 内容区域 -->
            <div class="right_content">
                <?= $content; ?>
            </div>
        </div>
    </div>
</div>
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

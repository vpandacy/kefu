<?php
use www\assets\MerchantAsset;
use \common\services\GlobalUrlService;
MerchantAsset::register($this);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" href="<?=GlobalUrlService::buildWwwStaticUrl("/images/favicon.ico");?>" type="image/x-icon"/>
    <title><?=Yii::$app->params["company"]["title"];?> -- 商户后台</title>
    <link href="//at.alicdn.com/t/font_1531636_sc0djrq72ao.css"  rel="stylesheet">
    <link href="<?=GlobalUrlService::buildWwwStaticUrl("/css/merchant/merchant.css");?>" rel="stylesheet">
</head>
<body>
    <div id="merchant">
        <div class="chant_all">
        <!-- 左侧菜单栏 -->
        <div class="left_menu"> 
            <div class="menu-logo">
                <a>
                <img src="<?=GlobalUrlService::buildWwwStaticUrl("/images/merchant/logo1.png");?>">
                </a>
            </div>
            <div class="menu-version">俱乐部版</div>
            <div class="menu-title">
                <a><i class="iconfont icon-yonghuguanli"></i></a>
                <a><i class="iconfont icon-liaotianguanli"></i></a>
                <a><i class="iconfont icon-quanjushezhi"></i></a>
                <a><i class="iconfont icon-heimingdan"></i></a>
                <a><i class="iconfont icon-fengge"></i></a>
            </div>
            <div class="menu-show-hide">
                <a><i class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-1"></i></a>
            </div>
        </div>
        <!-- 右侧菜单栏 -->
        <div class="right_merchant">
        <!-- 头部个人信息 -->
        <div class="right_top">
            <a><i class="iconfont icon-quanjushezhi"></i></a>
            <a><i class="iconfont icon-xinxi-copy"></i></a>
            <a><i class="iconfont icon-tongzhi"></i></a>
            <a><img src="<?=GlobalUrlService::buildWwwStaticUrl("/images/merchant/test.png");?>"></a>
        </div>
        <!-- 内容区域 -->
        <div class="right_content">
            <?php echo $content ?>
        </div>
        </div>
        </div>
    </div>
</body>
</html>

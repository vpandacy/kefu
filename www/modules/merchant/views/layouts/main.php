<?php
use www\assets\MerchantAsset;
use \common\services\GlobalUrlService;
MerchantAsset::register($this);
use \common\components\helper\StaticAssetsHelper;
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
        <!-- 左侧菜单栏 -->
        <div class="left_menu" id="left_menu">
            <div class="menu-logo">
                <a><img src="<?=GlobalUrlService::buildWwwStaticUrl("/images/merchant/logo1.png");?>"></a>
            </div>
            <div class="menu-version">俱乐部版</div>
            <div class="menu-title">
                <a data-url="/merchant/staff/" href="<?=GlobalUrlService::buildWWWUrl('/merchant/staff/index/index');?>">
                    <div class="menu-tooltip">用户管理</div>
                    <i class="iconfont icon-yonghuguanli li_active"></i>
                    <div class="menu-show dis_none">用户管理</div>
                </a>
                <a data-url="/merchant/chat/" href="<?=GlobalUrlService::buildWWWUrl('/merchant/chat/index/index');?>">
                    <div class="menu-tooltip">聊天管理</div>
                    <i class="iconfont icon-liaotian"></i>
                    <div class="menu-show dis_none">聊天管理</div>
                </a>
                <a data-url="/merchant/overall/" href="<?=GlobalUrlService::buildWWWUrl('/merchant/overall/index/index');?>">
                    <div class="menu-tooltip">全局设置</div>

                    <i class="iconfont icon-quanjushezhi"></i>
                    <div class="menu-show dis_none">全局设置</div>
                </a>
                <a data-url="/merchant/black/" href="<?=GlobalUrlService::buildWWWUrl('/merchant/black/index/index');?>">
                    <div class="menu-tooltip">黑名单管理</div>

                    <i class="iconfont icon-heimingdan"></i>
                    <div class="menu-show dis_none">黑名单管理</div>
                </a>
                <a data-url="/merchant/style/" href="<?=GlobalUrlService::buildWWWUrl('/merchant/style/index/index');?>">
                    <div class="menu-tooltip">风格管理</div>

                    <i class="iconfont icon-fengge"></i>
                    <div class="menu-show dis_none">风格管理</div>
                </a>
            </div>
            <div class="menu_bottom">
                <div class="menu-show-hide" onclick="menuLock()">
                    <a>
                        <i class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-1" ></i>
                    </a>
                </div>
                <div class="menu-show-hide dis_none" onclick="menuClose()">
                    <a>
                        <i class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu- " ></i>
                    </a>
                </div>
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
            <?= $content; ?>
        </div>
        </div>
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

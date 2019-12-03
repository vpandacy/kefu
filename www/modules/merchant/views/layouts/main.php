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
            <!-- 左侧菜单栏 -->
            <div class="left_menu" id="left_menu">
                <div class="menu-logo">
                    <a><img src="<?=GlobalUrlService::buildWwwStaticUrl("/images/merchant/logo1.png");?>"></a>
                </div>
                <div class="menu-version"><?=$this->params['merchant']['name']?></div>
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
                    <a href="<?=GlobalUrlService::buildMerchantUrl('/staff/index/edit',['staff_id'=>$this->params['staff']['id']])?>" class="menu_info_link">
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
                                <a href="<?=GlobalUrlService::buildMerchantUrl('/staff/index/edit',['staff_id'=>$this->params['staff']['id']])?>">编辑</a>
                            </div>
                        </div>
                        <div class="info_edit_two backFFF logout" style="cursor: pointer" onclick="location.href=common_ops.buildMerchantUrl('/user/logout');">
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
</body>
</html>
<?php $this->endPage() ?>

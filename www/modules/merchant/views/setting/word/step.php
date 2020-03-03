<?php
use www\assets\MerchantAsset;
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use \common\components\helper\StaticPluginHelper;
StaticPluginHelper::setDepend( MerchantAsset::className() );
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/setting/word/import.js'),StaticPluginHelper::getDepend() );
?>
<div id="word_import_wrap">
    <?= $this->renderFile('@www/modules/merchant/views/common/bar_menu.php', [
        'bar_menu' => 'settings',
        'current_menu' => 'common_words'
    ]) ?>
</div>

<div class="layui-fluid" style="margin-top: 5px;">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12 layui-col-lg12">
            <?php if ($step == 1): ?>
                <div class="layui-card">
                    <div class="layui-card-header">
                        <h2>第一步：导入常用语介绍
                            <a target="_blank" class="layui-btn pull-right"
                               href="<?= GlobalUrlService::buildStaticUrl("/excel/kf/kf_word_20200203.xlsx"); ?>">下载模板</a>
                        </h2>
                    </div>
                    <div class="layui-card-body layui-row layui-col-space10">
                        <?php if (isset($msg) && $msg): ?>
                            <h4 class="text-red"><?= $msg; ?></h4>
                        <?php endif; ?>
                        <p>1、请点击下载模版文件，按格式填写好信息后再进行导入。</p>
                        <p>2、导入信息如下图所示 </p>
                        <div class="layui-col-md12">
                            <p>
                                <img style="width: 100%;" class="h-img"
                                     src="<?= GlobalUrlService::buildStaticUrl("/excel/kf/images/1.png"); ?>">
                            </p>
                        </div>
                        <div class="layui-btn-container">
                            <a class="layui-btn layui-btn-fluid"
                               href="<?= GlobalUrlService::buildKFMerchantUrl('/setting/word/import',
                                   ['step' => 2]); ?>">下一步</a>
                        </div>
                    </div>
                </div>
            <?php elseif ($step == 2): ?>
                <div class="layui-card">
                    <div class="layui-card-header">
                        <h2>
                            第二步：导入文件
                            <a class="layui-btn pull-right"
                               href="<?= GlobalUrlService::buildKFMerchantUrl('/setting/word/import'); ?>">返回上一步</a>
                        </h2>
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-row">
                            <div class="layui-col-xs4 layui-col-xs-offset4 layui-col-md4 layui-col-md-offset4 layui-col-lg4 layui-col-lg-offset4">
                                <form class="layui-form" method="post" enctype="multipart/form-data"
                                      action="<?= GlobalUrlService::buildKFMerchantUrl('/setting/word/import',
                                          ['step' => 3]); ?>">
                                    <div class="layui-form-item">
                                        <input type="file" name="file" id="file">
                                    </div>
                                    <div class="layui-form-item">
                                        <button lay-submit class="layui-btn layui-btn-fluid layui-btn-sm">导入</button>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            <?php elseif ($step == 3): ?>
                <div class="layui-card step_3">
                    <div class="layui-card-header">
                        <h2>
                            第三步：确认文件内容
                        </h2>
                    </div>
                    <div class="layui-card-body">
                        <table class="layui-table">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>标题</th>
                                <th>内容</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($list): ?>
                                <?php foreach ($list as $_idx =>  $_item): ?>
                                <tr>
                                    <td><?=$_idx + 1;?></td>
                                    <td><?=$_item['title'];?></td>
                                    <td><?=$_item['words'];?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="layui-form-item">
                            <button data-input='<?=json_encode($list); ?>' class="layui-btn layui-btn-fluid save">确定导入</button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
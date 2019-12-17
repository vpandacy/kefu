<?php

use common\components\helper\StaticAssetsHelper;
use common\components\helper\StaticPluginHelper;
use common\components\helper\UtilHelper;
use common\services\GlobalUrlService;
use uc\services\ConstantService;

StaticPluginHelper::setDepend(\admin\assets\Asset::className());
StaticPluginHelper::daterangepicker();
StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFAdminUrl("/js/log/error.js"),StaticPluginHelper::getDepend());
?>
<section class="content">
    <div class="page-header">
        <h3>错误日志</h3>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-xs-12 col-md-12 col-lg-12">
                            <form class="form-inline wrap_search">


                                <div class="form-group" <?php if( UtilHelper::isPC() ):?> style="width: 14%;" <?php endif;?>>
                                    <input type="text" class="form-control" name="date_range_picker" placeholder="请选择日期~~" style="width: 100%;">
                                    <input type="hidden" class="form-control" name="date_from" value="<?= $search_conditions["date_from"]; ?>">
                                    <input type="hidden" class="form-control" name="date_to" value="<?= $search_conditions["date_to"]; ?>">
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">搜索</button>
                                </div>
                                <a class="btn btn-link" href="/log/error">清空搜索</a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding" >
                    <table class="table table-bordered table-striped"  <?php if( UtilHelper::isPC() ):?> style="table-layout:fixed;" <?php endif;?>>
                        <thead>
                        <tr>
                            <th width="6%">ID</th>
                            <th width="10%">应用</th>
                            <th width="10%">请求URI</th>
                            <th width="62%">错误内容</th>
                            <th width="12%">时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($list): ?>
                            <?php foreach ($list as $_item): ?>
                                <tr>
                                    <td><?=$_item['id'];?></td>
                                    <td><?=$_item['app_name'];?></td>
                                    <td style="overflow:hidden; word-wrap:break-word; word-break:normal;"><?=$_item['request_uri'];?></td>
                                    <td style="overflow:hidden; word-wrap:break-word; word-break:normal;"><?=$_item['content'];?></td>
                                    <td><?=$_item['created_time'];?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">暂无数据</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    <?=\Yii::$app->view->renderFile("@admin/views/common/pagination.php", [
                        'pages' => isset($pages) ? $pages : null,
                        'url' => '/log/error',
                        'search_conditions' => $search_conditions,
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

</section>
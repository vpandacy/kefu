<?php
use common\components\helper\UtilHelper;
?>
<section class="content">
    <div class="page-header">
        <h3>短信日志</h3>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="box">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="6%">ID</th>
                            <th width="6%">手机号码</th>
                            <th width="10%">签名</th>
                            <th>内容</th>
                            <th width="10%">IP</th>
                            <th width="12%">时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($list): ?>
                            <?php foreach ($list as $_item): ?>
                                <tr>

                                    <td><?=$_item['id'];?></td>
                                    <td><?=UtilHelper::maskStr( $_item['mobile'],3,4);?></td>
                                    <td><?=$_item['sign'];?></td>
                                    <td style="overflow:hidden; word-wrap:break-word; word-break:normal;"><?=$_item['content'];?></td>
                                    <td><?=$_item['ip'];?></td>
                                    <td><?=$_item['created_time'];?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">暂无数据</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    <?=\Yii::$app->view->renderFile("@admin/views/common/pagination.php", [
                        'pages' => isset($pages) ? $pages : null,
                        'url' => '/log/sms',
                        'search_conditions' => [],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

</section>
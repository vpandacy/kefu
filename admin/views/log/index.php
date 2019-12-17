<section class="content">
    <div class="page-header">
        <h3>访问日志</h3>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="box">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="12%">时间</th>
                            <th width="6%">姓名</th>
                            <th>请求URI</th>
                            <th width="10%">IP</th>
                            <th width="10%">IP城市</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($list): ?>
                            <?php foreach ($list as $_item): ?>
                                <tr>
                                    <td><?=$_item['created_time'];?></td>
                                    <td><?=$_item['staff_name'];?></td>
                                    <td style="overflow:hidden; word-wrap:break-word; word-break:break-all;"><?=$_item['target_url'];?></td>
                                    <td style="overflow:hidden; word-wrap:break-word; word-break:break-all;"><?=$_item['ip'];?></td>
                                    <td><?=$_item['ip_desc'];?></td>
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
                        'url' => '/log/index',
                        'search_conditions' => [],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

</section>
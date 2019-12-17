<section class="content">
    <div class="page-header">
        <h3>商户列表</h3>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="box">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="6%">ID</th>
                            <th width="12%">编号</th>
                            <th width="12%">名称</th>
                            <th>简介</th>
                            <th width="10%">联系方式</th>
                            <th width="10%">创建时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($list): ?>
                            <?php foreach ($list as $_item): ?>
                                <tr>
                                    <td><?=$_item['id'];?></td>
                                    <td><?=$_item['sn'];?></td>
                                    <td><?=$_item['name'];?></td>
                                    <td style="overflow:hidden; word-wrap:break-word; word-break:break-all;"><?=$_item['desc'];?></td>
                                    <td style="overflow:hidden; word-wrap:break-word; word-break:break-all;"><?=$_item['contact'];?></td>
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
                        'url' => '/log/index',
                        'search_conditions' => [],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

</section>
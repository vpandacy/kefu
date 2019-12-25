<?php
use common\components\helper\StaticPluginHelper;
use admin\assets\Asset;
use common\services\ConstantService;

StaticPluginHelper::setDepend(Asset::className());
?>
<section class="content">
    <div class="page-header">
        <h3>WS配置信息</h3>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="box">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="3%">ID</th>
                            <th width="10%">所属组</th>
                            <th width="8%">类型</th>
                            <th width="14%">名称</th>
                            <th>IP</th>
                            <th width="8%">监听端口</th>
                            <th width="10%">起始端口</th>
                            <th width="10%">所属注册中心</th>
                            <th width="10%">进程数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($monitor): ?>
                            <?php foreach($monitor as $item):?>
                                <tr>
                                    <td><?=$item['id']?></td>
                                    <td><?=$item['owner_group'] == 1 ? '游客组' : '客服组'?></td>
                                    <td><?=ConstantService::$worker_types[$item['type']]?></td>
                                    <td><?=$item['name']?></td>
                                    <td><?=$item['ip']?></td>
                                    <td><?=$item['port']?></td>
                                    <td><?=$item['start_port']?></td>
                                    <td><?=$item['owner_reg']?></td>
                                    <td><?=$item['count']?></td>
                                </tr>
                            <?php endforeach;?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">暂无数据</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
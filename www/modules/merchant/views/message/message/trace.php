<?php

use \common\components\DataHelper;
use \common\components\helper\DateHelper;
use \common\services\ConstantService;

?>

<table class="layui-hide" lay-skin="line" lay-filter="guest_trace">
    <thead>
    <tr>
        <th lay-data="{ field:'f1',width:150}">访问时间</th>
        <th lay-data="{ field:'f2'}">访问地址</th>
        <th lay-data="{ field:'f3',width:80}">停留时长</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?= $info['created_time']; ?></td>
        <td><?= DataHelper::encode($info['referer_url'] ?? "直接访问"); ?></td>
        <td>--</td>
    </tr>
    <tr>
        <td><?= $info['created_time']; ?></td>
        <td>
            <a class="btn-link" target="_blank" href="<?= DataHelper::encode($info['land_url']); ?>">
                <?= DataHelper::encode($info['land_url']); ?>
            </a>
        </td>
        <td>
            <?= DateHelper::getPrettyDuration($info['chat_duration']); ?>
        </td>
    </tr>
    </tbody>
</table>
<script type="text/javascript">
    var table = layui.table;
    //转换静态表格
    table.init('guest_trace');
</script>
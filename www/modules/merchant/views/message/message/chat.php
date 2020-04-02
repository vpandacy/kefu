<?php
use \common\components\helper\DataHelper;
?>
<?php if ($list): ?>
    <div class="content_assgin">
        <?php foreach ($list as $_item): ?>

            <?php if ($_item['from_id'] == $_item['uuid']): ?>
            <!-游客-->
                <div class="assgin_info">
                    <div class="assgin_title">
                        <?=DataHelper::getGuestNumber( $_item['uuid'] );?>&nbsp;&nbsp;<?=DataHelper::encode( $_item['created_time'] );?>
                    </div>
                    <div class="assgin_content">
                        <?=DataHelper::encode( $_item['content'] );?>
                    </div>
                </div>
            <?php else: ?>
                <!-客服-->
                <div class="assgin_info">
                    <div class="assgin_title as_title_my">
                        <?=DataHelper::encode( $_item['cs_name'] );?>&nbsp;&nbsp;<?=DataHelper::encode( $_item['created_time'] );?>
                    </div>
                    <div class="assgin_content">
                        <?=DataHelper::encode( $_item['content'] );?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php else:?>
    暂无对话
<?php endif; ?>
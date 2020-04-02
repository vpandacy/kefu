<?php

use \common\components\helper\DataHelper;
use \common\components\helper\DateHelper;
use \common\services\ConstantService;
use \common\components\ip\IPDBQuery;

?>
<div class="content_information">
    <div>
        <span class="information_title">开始时间：</span>
        <span><?= $info['created_time']; ?></span>
    </div>
    <div>
        <span class="information_title">结束时间：</span>
        <span><?= $info['closed_time']; ?></span>
    </div>
    <div>
        <span class="information_title">对话时长：</span>
        <span><?=DateHelper::getPrettyDuration( $info['chat_duration'] );?></span>
    </div>
    <div>
        <span class="information_title">地区：</span>
        <span><?= implode(" ",IPDBQuery::find( $info['client_ip'] ) );?></span></div>
    <div>
        <span class="information_title">访客IP：</span>
        <span><?= $info['client_ip']; ?></span>
    </div>
    <div>
        <span class="information_title">终端：</span>
        <span><?= ConstantService::$guest_source[ $info['source'] ]??""; ?></span>
    </div>
    <div>
        <span class="information_title">消息类型：</span>
        <span>在线消息</span>
    </div>
    <div>
        <span class="information_title">访问来源：</span>
        <span><?=DataHelper::encode( $info['referer_url']??"直接访问" );?></span>
    </div>
    <div>
        <span class="information_title">关键词：</span>
        <span><?=DataHelper::encode( $info['keyword'] );?></span>
    </div>
    <div>
        <span class="information_title">落地页：</span>
        <a class="btn-link" target="_blank" href="<?=DataHelper::encode( $info['land_url'] );?>">
            <?=DataHelper::encode( $info['land_url'] );?>
        </a>
    </div>
</div>
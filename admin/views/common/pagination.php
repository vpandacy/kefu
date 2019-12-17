<?php
use \common\services\GlobalUrlService;
?>
<?php if( $pages ):?>
<div class="row">
    <div class="col-md-2">
        <div class="pagination-count" style="line-height: 34px;" >
            共<?=$pages['total_count'];?>条记录 | 每页<?=$pages["page_size"];?>条
        </div>
    </div>
    <div class="col-md-10">
        <ul class="pagination  no-margin pull-right">
            <?php if($pages['previous']): ?>
            <li><a href="<?=$url?GlobalUrlService::buildAdminUrl($url,array_merge($search_conditions,[ 'p' => $pages['current'] - 1 ])):GlobalUrlService::buildNullUrl();?>" >上一页</a></li>
            <?php endif;?>
            <?php  for($page = $pages['from'];$page<=$pages['end'];$page++):?>
                <?php if($page == $pages['current']):?>
                    <li class="active"><a href="<?=GlobalUrlService::buildNullUrl();?>"><?=$page;?></a></li>
                <?php else:?>
                    <li><a href="<?=GlobalUrlService::buildAdminUrl($url,array_merge($search_conditions,[ 'p' => $page ]));?>"><?=$page;?></a></li>
                <?php endif;?>
            <?php endfor;?>

<!--            <li><a href="#">2</a></li>-->
<!--            <li><a href="#">3</a></li>-->
            <?php if($pages['next']): ?>
            <li><a href="<?=$url?GlobalUrlService::buildAdminUrl($url,array_merge($search_conditions,[ 'p' => $pages['current'] + 1 ])):GlobalUrlService::buildNullUrl();?>">下一页</a></li>
            <?php endif;?>
        </ul>
    </div>
</div>
<?php endif;?>

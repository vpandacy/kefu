<?php
use \common\services\GlobalUrlService;
?>
<?php if( $pages ):?>
<div class="layui-box layui-laypage layui-laypage-default pull-right" >
    <span class="layui-laypage-count">
        共<?=$pages['total_count'];?>条记录 | 每页<?=$pages["page_size"];?>条
    </span>

    <a href="<?=$pages['previous']?GlobalUrlService::buildKFUrl($url, array_merge($sc,[ 'p' => $pages['current'] - 1 ])):GlobalUrlService::buildNullUrl();?>" class="layui-laypage-prev <?php if(!$pages['previous']):?> layui-disabled <?php endif;?>">上一页</a>


    <?php  for($page = $pages['from'];$page<=$pages['end'];$page++):?>
        <?php if($page == $pages['current']):?>
            <span class="layui-laypage-curr">
            <em class="layui-laypage-em"></em><em><?=$page;?></em>
            </span>
        <?php else:?>
            <a href="<?=GlobalUrlService::buildKFUrl($url,array_merge($sc,[ 'p' => $page ]));?>"><?=$page;?></a>
        <?php endif;?>
    <?php endfor;?>

    <!---
    <span class="layui-laypage-spr">…</span>
    <a href="javascript:;" class="layui-laypage-last" title="尾页" data-page="10">10</a>
    -->


    <a href="<?=$pages['next']?GlobalUrlService::buildKFUrl($url, array_merge($sc,[ 'p' => $pages['current'] + 1 ])):GlobalUrlService::buildNullUrl();?>" class="layui-laypage-next <?php if(!$pages['next']):?> layui-disabled <?php endif;?>">上一页</a>


    <!--
    <span class="layui-laypage-skip">
        到第<input name="p" type="text" min="1" max="<?=$pages['total_page'];?>" value="<?=$pages['current'];?>" class="layui-input">页
        <button type="button" class="layui-laypage-btn">确定</button>
    </span>
    -->
</div>
<?php endif;?>

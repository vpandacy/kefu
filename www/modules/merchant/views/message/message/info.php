<?php
use \common\services\GlobalUrlService;
?>
<style>
    .tab-tools{
        position: absolute;
        top:0;
        right: 0;
        height: 40px;
        line-height: 40px;
        margin-right: 10px;
    }
    .tab-tools .iconfont{
        font-size: 20px;
        margin-right: 10px;
        font-weight: bold;
    }
    #pop_layer .pop_left{
        box-shadow:none;
    }
    #pop_layer .pop_right{
        margin-left: -28px;
        box-shadow:-1px 0px 4px 0px rgba(154,154,154,0.5), 1px 0px 0px 0px rgba(216,223,234,1);
    }
</style>
<div class="layui-row">
    <div class="layui-col-sm3 layui-col-md3 layui-col-lg3">
        <div class="layui-card pop_left">
            <div class="layui-card-body">
                <?php if( $list):?>
                <?php foreach ( $list as $_item ):?>
                <div class="layui-row" style="margin: 10px 0;">
                    <button data-id="<?=$_item["id"];?>" class="layui-btn chat_log">
                        <?=$_item["created_time"];?>
                    </button>
                </div>
                <?php endforeach;?>
                <?php endif;?>
            </div>
        </div>
    </div>
    <div class="layui-col-sm9 layui-col-md9 layui-col-lg9">
        <div class="layui-tab layui-tab-brief pop_right" style="margin-top: 0;">
            <ul class="layui-tab-title">
                <li class="layui-this">对话记录</li>
                <li>详细信息</li>
                <li>访问轨迹</li>
            </ul>
            <div class="pull-right tab-tools">
                <span class="iconfont icon-changyongtubiao-xianxingdaochu-zhuanqu-  prev"></span>
                <span class="iconfont icon-jiantou9 next"></span>
                <span class="iconfont icon-guanbi close"></span>
            </div>
            <div class="layui-tab-content">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=GlobalUrlService::buildWWWStaticUrl("/js/merchant/message/message/info.js");?>"></script>
</script>



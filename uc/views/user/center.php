<?php
use common\services\GlobalUrlService;
?>
<!--<a><i class="iconfont icon-quanjushezhi"></i></a>-->
<!--<a><i class="iconfont icon-xinxi-copy"></i></a>-->
<!--<a><i class="iconfont icon-tongzhi"></i></a>-->
<a href="javascript:;" class="menu_info_link">
    <img class="menu_info_img" src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$this->params['current_user']['avatar'])?>">
</a>
<div class="menu_info_edit dis_none">
    <div class="info_edit_one">
        <div >
            <img src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$this->params['current_user']['avatar']);?>">
        </div>
        <div>
            <div class="info_ms_two">
                <label><?=$this->params['current_user']['name']?></label>
            </div>
            <div class="info_ms_three">
                <label><?=$this->params['current_user']['mobile'] ? $this->params['current_user']['mobile'] : '暂无手机号'?></label>
            </div>
        </div>
        <div>
            <a href="<?=GlobalUrlService::buildUcUrl('/company/index',['staff_id'=>$this->params['current_user']['id']])?>">编辑</a>
        </div>
    </div>
    <?php if(count($app_ids) > 1):?>
        <a class="info_edit_two backFFF logout" href="<?=GlobalUrlService::buildUcUrl('/default/application');?>">
            <div>
                <i class="iconfont icon-diannao"></i>
            </div>
            <div>切换应用</div>
        </a>
    <?php endif;?>
    <a class="info_edit_two backFFF logout" href="<?=GlobalUrlService::buildKFCSUrl('/');?>">
        <div>
            <i class="iconfont icon-diannao"></i>
        </div>
        <div>工作台</div>
    </a>
    <a class="info_edit_two backFFF logout" href="<?=GlobalUrlService::buildUcUrl("/staff/edit",['staff_id'=>$this->params['current_user']['id']]);?>">
        <div>
            <i class="iconfont icon-shezhi"></i>
        </div>
        <div>修改密码</div>
    </a>
    <a class="info_edit_two backFFF logout" href="<?=GlobalUrlService::buildUcUrl("/user/logout");?>">
        <div>
            <i class="iconfont icon-tuichu"></i>
        </div>
        <div>退出</div>
    </a>
</div>
<?php
use uc\service\UcUrlService;
use common\services\GlobalUrlService;

?>
<a><i class="iconfont icon-quanjushezhi"></i></a>
<a><i class="iconfont icon-xinxi-copy"></i></a>
<a><i class="iconfont icon-tongzhi"></i></a>
<a href="<?=UcUrlService::buildUcUrl('/staff/edit',['staff_id'=>$this->params['staff']['id']])?>" class="menu_info_link">
    <img class="menu_info_img" src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$this->params['staff']['avatar']);?>">
</a>
<div class="menu_info_edit dis_none">
    <div class="info_edit_one">
        <div >
            <img src="<?=GlobalUrlService::buildPicStaticUrl('hsh',$this->params['staff']['avatar']);?>">
        </div>
        <div>
            <div class="info_ms_two">
                <label><?=$this->params['staff']['name']?></label>
            </div>
            <div class="info_ms_three">
                <label><?=$this->params['staff']['mobile'] ? $this->params['staff']['mobile'] : '暂无手机号'?></label>
            </div>
        </div>
        <div>
            <a href="<?=UcUrlService::buildUcUrl('/staff/edit',['staff_id'=>$this->params['staff']['id']])?>">编辑</a>
        </div>
    </div>
    <div class="info_edit_two backFFF logout" style="cursor: pointer" onclick="location.href=url_manager.buildUcUrl('/user/logout');">
        <div>
            <i class="iconfont icon-tuichu"></i>
        </div>
        <div>退出</div>
    </div>
</div>
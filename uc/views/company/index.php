<?php
use common\components\helper\StaticAssetsHelper;
use common\components\helper\StaticPluginHelper;
use common\services\GlobalUrlService;
use uc\assets\UcAsset;


StaticPluginHelper::setDepend(UcAsset::className());
StaticPluginHelper::qiniuPlugin();

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildUcStaticUrl('/js/company/index.js'), UcAsset::className() )
?>
<div id="staff_index_index">
    <?=$this->renderFile('@uc/views/common/bar_menu.php',[
        'bar_menu'  =>  'settings',
        'current_menu'  =>  'company'
    ])?>
    <div class="tab_staff_content">
        <ul class="mainmenu">
            <li><span>基本信息</span></li>
            <ul class="submenu">
                <form action="" class="layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">公司名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" value="<?=$merchant['name']?>"  required="" lay-verify="required" placeholder="请输入公司名/商户名" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">联系方式</label>
                        <div class="layui-input-block">
                            <input type="text" name="contact" value="<?=$merchant['contact']?>" required="" lay-verify="required" placeholder="请输入联系方式" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">公司LOGO</label>
                        <div class="layui-input-block" id="upload_container">
                            <button type="button" class="layui-btn" id="logo">
                                <i class="layui-icon"></i>上传图片
                            </button>
                            <input type="hidden" value="<?=$merchant['logo']?>" name="logo">
                        </div>
                        <div class="img-wrapper" style="margin-top: 10px;">
                            <?php if($merchant['logo']) :?>
                                <div class="layui-input-block">
                                    <img width="100" height="100" src="<?=GlobalUrlService::buildPicStaticUrl('hsh', $merchant['logo'])?>" alt="">
                                </div>
                            <?php endif;?>
                        </div>
                    </div>

                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">公司简介</label>
                        <div class="layui-input-block">
                            <textarea name="desc" placeholder="请输入公司简介" class="layui-textarea"><?=$merchant['desc']?></textarea>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="info">保存</button>
                        </div>
                    </div>
                </form>
            </ul>

            <li><span>聊天配置</span></li>
            <ul class="submenu">
                <form action="" class="layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">自动断开</label>
                        <div class="layui-input-block">
                            <input type="text" name="auto_disconnect" value="<?=$setting['auto_disconnect']?>" required="" lay-verify="required" placeholder="请输入自动断开时间(秒)" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">问候语</label>
                        <div class="layui-input-block">
                            <textarea name="greetings" required="" lay-verify="required" placeholder="请输入首次的问候语" class="layui-textarea"><?=$setting['greetings']?></textarea>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="settings">保存</button>
                        </div>
                    </div>
                </form>
            </ul>
        </ul>
    </div>
</div>
<?php
use common\services\GlobalUrlService;
use common\components\helper\StaticAssetsHelper;
use www\assets\MerchantAsset;
use www\services\MerchantConstantService;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildKFStaticUrl('/js/merchant/style/reception/index.js'), MerchantAsset::className());
?>
<div id="staff_index_index">
    <div class="staff_tab">
        <?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
            'bar_menu'  =>  'style',
            'current_menu'  =>  'reception'
        ])?>
    </div>
    <div class="tab_staff_content">
        <form action="" class="layui-form">
            <div class="site-text">
                <fieldset class="layui-elem-field">
                    <legend>风格分组</legend>
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">风格</label>
                            <div class="layui-inline">
                                <select name="group_chat_id" lay-filter="choice">
                                    <option value="0">普通风格</option>
                                    <?php foreach($groups as $group):?>
                                        <option value="<?=$group['id']?>"><?=$group['title']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>分配设置</legend>
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">分配方式</label>
                            <div class="layui-input-block">
                                <?php foreach(MerchantConstantService::$group_distribution_modes as $id=>$title):?>
                                    <input type="radio" name="distribution_mode" value="<?=$id?>" title="<?=$title?>">
                                <?php endforeach;?>
                            </div>
                            <div class="layui-form-mid layui-word-aux">自动分配是由程序自动为游客分配一个客服</div>
                        </div>

                        <!-- 如果是风格分组优先,则默认以该风格下所有成员为准 -->
                        <div class="layui-form-item">
                            <label class="layui-form-label">接待策略</label>
                            <div class="layui-input-block">
                                <?php foreach(MerchantConstantService::$group_reception_strategies as $id=>$title):?>
                                    <input type="radio" name="reception_strategy" value="<?=$id?>" title="<?=$title?>">
                                <?php endforeach;?>
                            </div>
<!--                            <div class="layui-form-mid layui-word-aux">分配方式为手动分配时有效,优先展示管理员或者风格组成员</div>-->
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">接待优先</label>
                            <div class="layui-input-block">
                                <?php foreach(MerchantConstantService::$group_reception_rules as $id=>$title):?>
                                    <input type="radio" name="reception_rule" value="<?=$id?>" title="<?=$title?>">
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>分流设置</legend>
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label">分流类型</label>
                            <div class="layui-input-block">
                                <?php foreach(MerchantConstantService::$group_shunt_modes as $id=>$title):?>
                                    <input type="radio" name="shunt_mode" value="<?=$id?>" title="<?=$title?>">
                                <?php endforeach;?>
                            </div>
<!--                            <div class="layui-form-mid layui-word-aux">目前仅支持指定客服,如需设置请前往风格管理->分配客服</div>-->
                        </div>

                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="info">立即保存</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </form>
    </div>
</div>
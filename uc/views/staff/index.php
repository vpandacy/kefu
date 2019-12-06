<?php
use uc\service\UcUrlService;
use common\components\helper\StaticAssetsHelper;
use uc\assets\UcAsset;
/**
 * @var \yii\web\View $this
 */

StaticAssetsHelper::includeAppJsStatic( UcUrlService::buildUcStaticUrl("/js/staff/index.js"),UcAsset::className() )
?>
<div id="staff_index_index">
    <?=$this->renderFile('@uc/views/common/bar_menu.php',[
        'bar_menu'  =>  'user',
        'current_menu'  =>  'sub_user'
    ])?>
    <div class="tab_staff_content">
        <table class="layui-hide" lay-filter="staff" id="staff" style="position: relative">
        </table>
    </div>
</div>
<script type="text/html" id="staffBar">
    <div class="layui-btn-container">
        <div>
            <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
            <button class="layui-btn layui-btn-sm" lay-event="recover">恢复</button>
        </div>
        <div>
            <i class="fa fa-glass" aria-hidden="true" title="筛选"></i>
        </div>
    </div>
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">禁用</a>
</script>


<div class="search-wrapper" style="display: none">
    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-block">
            <input type="text" name="mobile" value="<?=$search_conditions['mobile']?>" placeholder="请输入手机号" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-block">
            <input type="text" name="email" value="<?=$search_conditions['email']?>" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-form-item">
            <label class="layui-form-label">所属行业</label>
            <div class="layui-input-block">
                <select name="department_id">
                    <option value="0">请选择行业</option>
                    <?php foreach($departments as $department):?>
                        <option value="<?=$department['id']?>" <?=$department['id'] == $search_conditions['department_id'] ? 'selected' : ''?>>
                            <?=$department['name']?>
                        </option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-inline">
            <button class="layui-btn" lay-submit lay-filter="searchForm">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</div>
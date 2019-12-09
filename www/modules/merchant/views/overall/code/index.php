<?php
use common\services\GlobalUrlService;
use www\assets\MerchantAsset;
use common\components\helper\StaticAssetsHelper;

StaticAssetsHelper::includeAppJsStatic(GlobalUrlService::buildWwwStaticUrl('/js/merchant/overall/code/index.js'), MerchantAsset::className());
?>
<?=$this->renderFile('@www/modules/merchant/views/common/bar_menu.php',[
    'bar_menu'  =>  'settings',
    'current_menu'  =>  'code'
])?>

<div class="tab_staff_content">
    <fieldset>
        <legend>
            <a name="use">获取客服代码</a>
        </legend>
    </fieldset>

    <form class="layui-form form-inline">
        <div class="layui-card">
            <div style="background: #F2F2F2; padding: 10px 0 10px 10px;">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <select name="role_id" lay-filter="choice" lay-verify="required" lay-search="">
                            <option value="0">请选择风格</option>
                            <option value="9999">普通风格</option>
                            <option value="1">风格1</option>
                            <option value="2">风格2</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div style="background: #F2F2F2; padding: 20px" id="style-script">
        <pre>请选择风格</pre>
    </div>
    <div class="layui-card" style="margin-top: 10px;">
        <h4>代码安装说明</h4>
        <pre style="color: red;">
    1. 请将代码添加到网站全部页面的&lt;/head&gt;标签前。
    2. 建议在header.htm类似的页头模板页面中安装，以达到一处安装，全站皆有的效果。
    3. 如需在JS文件中调用统计分析代码，请直接去掉以下代码首尾的，&lt;script type=&quot;text/javascript&quot;&gt;与&lt;/script&gt;后，放入JS文件中即可。
        </pre>
    </div>
</div>
<?php
use \common\services\GlobalUrlService;
use \common\components\helper\StaticAssetsHelper;
StaticAssetsHelper::includeAppJsStatic( GlobalUrlService::buildWwwStaticUrl("/js/merchant/staff/index/index.js"),www\assets\MerchantAsset::className() );
StaticAssetsHelper::includeAppCssStatic( GlobalUrlService::buildWwwStaticUrl(""),www\assets\MerchantAsset::className() )
?>


<?php

use common\services\GlobalUrlService;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>error</title>
    <link href="<?=GlobalUrlService::buildKFStaticUrl('/css/www/error/error.css')?>" rel="stylesheet" />
</head>
<body>
<div id="error_kefu">
    <img src="<?=GlobalUrlService::buildKFStaticUrl('/images/www/error/error.png')?>">
</div>
</body>
</html>
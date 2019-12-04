<?php

use common\services\GlobalUrlService;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>error</title>
    <link href="<?=GlobalUrlService::buildWwwStaticUrl('/css/www/error/error.css')?>" rel="stylesheet" />
</head>
<body>
<div id="error_kefu">
    <img src="<?=GlobalUrlService::buildWwwStaticUrl('/images/www/error/error.png')?>">
</div>
</body>
</html>
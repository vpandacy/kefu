<?php
namespace uc\controllers;

use common\services\QiniuService;
use uc\controllers\common\BaseController;

class UploadController extends BaseController
{
    public function actionQiniuToken()
    {
        $token = QiniuService::getUploadKey(null,'hsh');

        return json_encode([ 'uptoken' => $token ]);
    }
}
<?php
namespace www\modules\merchant\controllers;

use common\services\QiniuService;
use www\modules\merchant\controllers\common\BaseController;

class UploadController extends BaseController
{
    public function actionQiniuToken()
    {
        $token = QiniuService::getUploadKey(null,'hsh');

        return json_encode([ 'uptoken' => $token ]);
    }
}
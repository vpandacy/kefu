<?php
namespace www\modules\merchant\controllers\user;

use www\modules\merchant\controllers\common\BaseController;

class IndexController extends BaseController
{
    public function actionIndex()
    {
        if($this->isGet()) {
            return $this->render('index');
        }


    }
}
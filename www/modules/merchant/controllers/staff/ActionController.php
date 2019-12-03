<?php
namespace www\modules\merchant\controllers\staff;

use www\modules\merchant\controllers\common\BaseController;

class ActionController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
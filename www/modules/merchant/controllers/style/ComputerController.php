<?php

namespace www\modules\merchant\controllers\style;

use www\modules\merchant\controllers\common\BaseController;

class ComputerController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}

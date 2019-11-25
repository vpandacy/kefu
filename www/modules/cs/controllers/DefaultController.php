<?php

namespace www\modules\cs\controllers;

use www\modules\cs\controllers\common\BaseController;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}

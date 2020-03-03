<?php

namespace www\modules\merchant\controllers;

use www\modules\merchant\controllers\common\BaseController;


class DefaultController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionForbidden()
    {
        $this->layout = false;
        return $this->render('forbidden');
    }
}

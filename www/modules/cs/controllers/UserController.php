<?php

namespace www\modules\cs\controllers;

use www\modules\cs\controllers\common\BaseController;

class UserController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('login');
    }
}

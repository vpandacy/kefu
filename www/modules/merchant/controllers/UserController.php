<?php

namespace www\modules\merchant\controllers;

use www\modules\merchant\controllers\common\BaseController;

/**
 * Default controller for the `merchant` module
 */
class UserController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionLogin()
    {
        return $this->render('login');
    }
}

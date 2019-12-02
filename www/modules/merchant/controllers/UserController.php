<?php

namespace www\modules\merchant\controllers;

use common\services\ConstantService;
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
        $this->layout = 'basic';
        return $this->render('login');
    }

    /**
     * 登录操作.
     */
    public function actionDoLogin()
    {


        return $this->renderJSON([],'登录成功', ConstantService::$response_success);
    }
}

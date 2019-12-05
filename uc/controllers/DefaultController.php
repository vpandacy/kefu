<?php
namespace uc\controllers;

use uc\controllers\common\BaseController;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        $this->layout = false;
        var_dump($this->get('app_str'));
        return 'hello world';
    }
}

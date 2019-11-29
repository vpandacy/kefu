<?php

namespace www\modules\merchant\controllers\staff;

use www\modules\merchant\controllers\common\BaseController;

/**
 * Default controller for the `merchant` module
 */
class DepartmentController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}

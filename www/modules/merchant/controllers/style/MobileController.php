<?php

namespace www\modules\merchant\controllers\style;

use www\modules\merchant\controllers\common\BaseController;

/**
 * Default controller for the `merchant` module
 */
class MobileController extends BaseController
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

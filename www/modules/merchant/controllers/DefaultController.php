<?php

namespace www\modules\merchant\controllers;

use common\services\GlobalUrlService;
use www\modules\merchant\controllers\common\BaseController;

/**
 * Default controller for the `merchant` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(GlobalUrlService::buildUcUrl('/staff/index'));
        return $this->render('index');
    }

    public function actionForbidden()
    {
        $this->layout = false;
        return $this->render('forbidden');
    }
}

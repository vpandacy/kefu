<?php

namespace admin\controllers;

use admin\controllers\common\BaseController;

class DefaultController extends BaseController
{
    public function actionIndex()
    {
        $this->layout='main';
        return $this->render('index');
    }

    public function actionForbidden()
    {
        return $this->render('forbidden', ["msg" => $this->get("url", "")]);
    }
    /**
     * 菜单栏右上角
     */
    public function actionMenu(){

        $content = $this->renderPartial("menu",[ "current_user" => $this->current_user   ]);
        return $this->renderJSON([ "content" => $content ]);
    }

}

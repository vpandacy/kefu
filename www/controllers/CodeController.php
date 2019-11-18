<?php
namespace www\controllers;

use www\controllers\common\BaseController;

class CodeController extends BaseController
{
    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = false;
    }

    public function actionIndex(){
        header('Content-type: text/javascript');
        return $this->render("index");
    }

    public function actionChat(){
        return $this->render("chat_mini");
    }
    public function actionOnline(){
        return $this->render("online");
    }
}

<?php
namespace www\controllers;

use www\controllers\common\BaseController;

/**
 * 游客端
 * Class CodeController
 * @package www\controllers
 */
class CodeController extends BaseController
{
    public function __construct($id, $module, $config = []){
        parent::__construct($id, $module, $config = []);
        $this->layout = false;
    }

    public function actionIndex()
    {
        header('Content-type: text/javascript');
        return $this->render("index.js");
    }

    public function actionChat()
    {
        return $this->render("chat_mini");
    }

    public function actionOnline()
    {
        return $this->render("online");
    }
}

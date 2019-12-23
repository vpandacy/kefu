<?php
namespace www\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex(){
        $this->layout = 'main';
        return $this->render('index');
    }

    public function actionTest()
    {
        $this->layout = 'main';
        return $this->render('index2');
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/12/17
 * Time: 20:19
 */

namespace admin\controllers;

use admin\controllers\common\BaseController;
use common\components\helper\UtilHelper;
use common\models\uc\Merchant;
use common\services\ConstantService;


class MerchantController extends BaseController
{
    public function actionIndex()
    {
        $kw = trim($this->get('kw', ''));  //姓名，昵称，手机号，邮箱
        $p = intval($this->get('p', 1));
        $query = Merchant::find()->where(['status' => ConstantService::$default_status_true]);
        if ($kw && is_numeric($kw)) {
            //手机号
            $query->andWhere(['contact' => $kw]);
        } elseif ($kw) {
            $query->andWhere(['like', 'name', '%' . strtr($kw, ['%' => '\%', '_' => '\_', '\\' => '\\\\']) . '%', false]);
        }
        $offset = ($p - 1) * $this->page_size;
        $page = UtilHelper::ipagination([
            'total_count' => $query->count(),
            'page' => $p,
            'page_size' => $this->page_size,
            'display' => 10
        ]);

        $list = [];
        $list = $query->offset($offset)->limit($this->page_size)->asArray()->all();

        $search_conditions=[
            'kw' =>$kw,
        ];

        return $this->render('index',[
            'list' =>$list,
            'search_conditions' =>$search_conditions,
            'pages' =>$page
        ]);
    }
}
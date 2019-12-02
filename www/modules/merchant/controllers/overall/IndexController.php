<?php

namespace www\modules\merchant\controllers\overall;

use common\models\CommonWords;
use www\modules\merchant\controllers\common\BaseController;

/**
 * Default controller for the `merchant` module
 */
class IndexController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取常用语言的数据.
     */
    public function actionList()
    {
        $page = intval($this->get('page',1));

        $page_size = $this->page_size;

        $data = CommonWords::find()
            // 暂时的.后面来处理这个公共的.
            ->where(['merchant_id'=>0])
            ->limit($this->page_size)
            ->offset($page_size * ($page - 1))
            ->asArray()
            ->all();

        // 根据layui的分页来处理.
        return $this->renderJSON($data,'获取成功', 0);
    }
}
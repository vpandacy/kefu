<?php
namespace www\modules\merchant\controllers\overall;

use common\components\DataHelper;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

class CodeController extends BaseController
{
    /**
     * 获取客服代码.
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取客服的js代码.
     */
    public function actionObtain()
    {
        $code = $this->renderPartial('obtain');

        return $this->renderJSON($code,'获取成功', ConstantService::$response_code_success);
    }
}
<?php
namespace www\modules\merchant\controllers\overall;

use common\models\merchant\MerchantSetting;
use www\modules\merchant\controllers\common\BaseController;

class CompanyController extends BaseController
{
    /**
     * 公司信息和配置.
     * @return string
     */
    public function actionIndex()
    {
        $merchant = $this->merchant_info;

        $setting = MerchantSetting::findOne(['merchant_id'=>$merchant['id']]);

        return $this->render('index',[
            'merchant'  =>  $merchant,
            'setting'   =>  $setting
        ]);
    }

    /**
     *
     */
    public function actionSave()
    {

    }
}
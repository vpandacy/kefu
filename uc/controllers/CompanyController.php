<?php
namespace uc\controllers;

use common\components\helper\ValidateHelper;
use common\models\uc\Merchant;
use common\models\uc\MerchantSetting;
use common\services\ConstantService;
use common\services\uc\MerchantService;
use uc\controllers\common\BaseController;

class CompanyController extends BaseController
{
    /**
     * 公司信息和配置.
     * @return string
     */
    public function actionIndex()
    {
        $merchant = Merchant::findOne(['id'=>$this->merchant_info['id']]);
        $setting = MerchantSetting::findOne(['merchant_id'=>$merchant['id']]);

        return $this->render('index',[
            'merchant'  =>  $merchant,
            'setting'   =>  $setting
        ]);
    }

    /**
     * 保存商户基本信息.
     */
    public function actionSave()
    {
        $name = $this->post('name','');
        $contact = $this->post('contact','');
        $logo = $this->post('logo','');
        $desc = $this->post('desc','');
        $auto_disconnect = $this->post('auto_disconnect','');
        $greetings = $this->post('greetings','');

        if(!ValidateHelper::validLength($name, 1, 255)) {
            return $this->renderErrJSON( '请填写正确的企业名称' );
        }

        if(!$contact) {
            return $this->renderErrJSON( '请填写联系方式' );
        }

        if(!$logo) {
            return $this->renderErrJSON( '请上传企业的logo' );
        }

        if(!ValidateHelper::validLength($desc, 1, 255)) {
            return $this->renderErrJSON(  '请填写对应的企业描述' );
        }

        if(!preg_match('/^\d+$/',$auto_disconnect)) {
            return $this->renderErrJSON( '请填写正确的自动断开时长' );
        }

        if(!ValidateHelper::validLength($greetings, 1, 255)) {
            return $this->renderErrJSON(  '请填写对应的企业问候语' );
        }

        if(!MerchantService::updateMerchant($this->getMerchantId(), $this->getAppId(), $logo, $contact, $name, $desc)) {
            return $this->renderErrJSON(MerchantService::getLastErrorMsg());
        }

        $setting = MerchantSetting::findOne(['merchant_id'=>$this->getMerchantId()]);

        if(!$setting) {
            $setting = new MerchantSetting();
        }

        $setting->setAttributes([
            'auto_disconnect'   =>  $auto_disconnect,
            'greetings'         =>  $greetings,
            'merchant_id'       =>  $this->getMerchantId()
        ],0);

        if(!$setting->save(0)) {
            return $this->renderErrJSON( '数据保存失败,请联系管理员' );
        }

        return $this->renderJSON([],'保存成功');
    }
}
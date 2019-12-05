<?php
namespace uc\controllers;

use common\models\uc\Merchant;
use common\models\uc\MerchantSetting;
use common\services\ConstantService;
use uc\controllers\common\BaseController;

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
     * 保存商户基本信息.
     */
    public function actionSaveInfo()
    {
        $name = $this->post('name','');
        $contact = $this->post('contact','');
        $logo = $this->post('logo','');
        $desc = $this->post('desc','');

        if(!$name || mb_strlen($name) > 255) {
            return $this->renderJSON([],'请填写正确的企业名称', ConstantService::$response_code_fail);
        }

        if(!$contact) {
            return $this->renderJSON([],'请填写联系方式', ConstantService::$response_code_fail);
        }

        if(!$logo) {
            return $this->renderJSON([],'请上传企业的logo', ConstantService::$response_code_fail);
        }

        if(!$desc || mb_strlen($desc) > 255) {
            return $this->renderJSON([], '请填写对应的企业描述', ConstantService::$response_code_fail);
        }

        $merchant = Merchant::findOne(['id'=>$this->getMerchantId(),'app_id'=>$this->getAppId()]);

        $merchant->setAttributes([
            'logo'  =>  $logo,
            'contact'   =>  $contact,
            'name'  =>  $name,
            'desc'  =>  $desc
        ],0);

        if(!$merchant->save(0)) {
            return $this->renderJSON([],'数据保存失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'保存成功', ConstantService::$response_code_success);
    }


    /**
     * 保存商户聊天配置信息.
     */
    public function actionSaveSetting()
    {
        $auto_disconnect = $this->post('auto_disconnect','');
        $greetings = $this->post('greetings','');

        if(!preg_match('/^\d+$/',$auto_disconnect)) {
            return $this->renderJSON([],'请填写正确的自动断开时长', ConstantService::$response_code_fail);
        }

        if(!$greetings || mb_strlen($greetings) > 255) {
            return $this->renderJSON([], '请填写对应的企业问候语', ConstantService::$response_code_fail);
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
            return $this->renderJSON([],'数据保存失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'保存成功', ConstantService::$response_code_success);
    }
}
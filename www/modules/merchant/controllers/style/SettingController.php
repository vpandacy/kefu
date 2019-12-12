<?php
namespace www\modules\merchant\controllers\style;

use common\components\helper\ValidateHelper;
use common\models\merchant\City;
use common\models\merchant\GroupChat;
use common\models\merchant\GroupChatSetting;
use www\modules\merchant\controllers\common\BaseController;

class SettingController extends BaseController
{
    public function actionIndex()
    {
        // 获取所有的风格信息.
        $groups = GroupChat::find()
            ->where(['merchant_id'=>$this->getMerchantId()])
            ->asArray()
            ->all();

        $city = City::find()
            ->where(['city_id'=>0])
            ->select(['id','name'])
            ->asArray()
            ->all();

        return $this->render('index', [
            'groups'    =>  $groups,
            'city'      =>  $city
        ]);
    }

    /**
     * 获取风格分组配置.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionInfo()
    {
        $id = intval($this->post('group_chat_id', 0));

        $setting = GroupChatSetting::find()
            ->where([
                'group_chat_id'    =>  $id,
                'merchant_id'      =>  $this->getMerchantId()
            ])
            ->asArray()
            ->one();

        return $this->renderJSON($setting, '获取成功');
    }

    /**
     * 保存信息.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionSave()
    {
        $data = $this->post(null);

        $request_r = [
            'company_name','company_logo','company_desc','province_id','is_history',
            'is_active','windows_status','is_force', 'is_show_num', 'group_chat_id'
        ];

        if(count(array_intersect(array_keys($data), $request_r)) != count($request_r)) {
            return $this->renderErrJSON( '参数丢失' );
        }

        if(!ValidateHelper::validLength($data['company_name'], 1, 255)) {
            return $this->renderErrJSON( '请输入公司名称~~' );
        }

        if(!ValidateHelper::validIsEmpty($data['company_logo'])) {
            return $this->renderErrJSON( '请上传公司LOGO~~' );
        }

        if(!ValidateHelper::validLength($data['company_desc'], 1, 255)) {
            return $this->renderErrJSON( '请输入公司描述~~' );
        }

        if(!in_array($data['is_history'], [0, 1])) {
            return $this->renderErrJSON( '请选择正确的展示消息记录' );
        }

        if(!in_array($data['is_active'], [0, 1])) {
            return $this->renderErrJSON( '请选择主动发起对话' );
        }

        if(!in_array($data['windows_status'], [0, 1])) {
            return $this->renderErrJSON( '请选择正确的浮窗状态' );
        }

        if(!in_array($data['is_force'], [0, 1])) {
            return $this->renderErrJSON( '请选择正确的新消息强制弹窗' );
        }

        if(!in_array($data['is_show_num'], [0, 1])) {
            return $this->renderErrJSON( '请选择正确的消息展示' );
        }

        // 检查对应的信息.
        $group_chat_ids = GroupChat::find()
            ->where(['merchant_id'=>$this->getMerchantId()])
            ->select(['id'])
            ->column();

        if($data['group_chat_id'] != 0 && !in_array($data['group_chat_id'], $group_chat_ids)) {
            return $this->renderErrJSON('暂无此风格信息~~');
        }

        $setting = GroupChatSetting::findOne([
            'group_chat_id' =>  $data['group_chat_id'],
            'merchant_id'   =>  $this->getMerchantId(),
        ]);

        if(!$setting) {
            $setting = new GroupChatSetting();
        }

        $data['merchant_id'] = $this->getMerchantId();

        $setting->setAttributes($data,0);

        if(!$setting->save(0)) {
            return $this->renderErrJSON( '数据保存失败,请联系管理员' );
        }

        return $this->renderJSON([],'保存成功');
    }
}
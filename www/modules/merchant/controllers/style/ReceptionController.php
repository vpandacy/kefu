<?php
namespace www\modules\merchant\controllers\style;

use common\models\merchant\GroupChat;
use common\models\merchant\ReceptionRule;
use common\services\uc\MerchantService;
use www\modules\merchant\controllers\common\BaseController;
use www\services\MerchantConstantService;

class ReceptionController extends BaseController
{
    public function actionIndex()
    {
        // 获取所有的风格信息.
        $groups = GroupChat::find()
            ->where(['merchant_id'=>$this->getMerchantId()])
            ->asArray()
            ->all();

        return $this->render('index', [
            'groups'    =>  $groups
        ]);
    }

    /**
     * 获取风格分组配置.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionInfo()
    {
        $id = intval($this->post('group_chat_id', 0));

        $rule = ReceptionRule::find()
            ->where([
                'group_chat_id'    =>  $id,
                'merchant_id'      =>  $this->getMerchantId()
            ])
            ->asArray()
            ->one();

        if(!$rule) {
            $rule = MerchantService::genDefaultReceptionRuleConfig();
        }

        return $this->renderJSON($rule, '获取成功');
    }

    /**
     * 保存接待规则.
     */
    public function actionSave()
    {
        $group_chat_id = intval($this->post('group_chat_id', 0));
        $distribution_mode = intval($this->post('distribution_mode',0));
        $reception_rule = intval($this->post('reception_rule',0));
        $reception_strategy= intval($this->post('reception_strategy',0));
        $shunt_mode = intval($this->post('shunt_mode',0));;

        // 检查对应的信息.
        $group_chat_ids = GroupChat::find()
            ->where(['merchant_id'=>$this->getMerchantId()])
            ->select(['id'])
            ->column();

        if($group_chat_id != 0 && !in_array($group_chat_id, $group_chat_ids)) {
            return $this->renderErrJSON('暂无此风格信息~~');
        }

        if(!in_array($distribution_mode, array_keys(MerchantConstantService::$group_distribution_modes))) {
            return $this->renderErrJSON('请选择正确的分配方式~~');
        }

        if(!in_array($reception_rule, array_keys(MerchantConstantService::$group_reception_rules))) {
            return $this->renderErrJSON('请选择正确的接待规则~~');
        }

        if(!in_array($reception_strategy, array_keys(MerchantConstantService::$group_reception_strategies))) {
            return $this->renderErrJSON('请选择正确的接待策略~~');
        }

        if(!in_array($shunt_mode, array_keys(MerchantConstantService::$group_shunt_modes))) {
            return $this->renderErrJSON('请选择正确的	分流规则~~');
        }

        // 开始进行保存.
        $rule = ReceptionRule::findOne([
            'merchant_id' => $this->getMerchantId(),
            'group_chat_id' => $group_chat_id
        ]);

        if(!$rule) {
            $rule = new ReceptionRule();
        }

        $rule->setAttributes([
            'merchant_id'   =>  $this->getMerchantId(),
            'group_chat_id' =>  $group_chat_id,
            'distribution_mode' =>$distribution_mode,
            'reception_rule'    =>  $reception_rule,
            'reception_strategy'=> $reception_strategy,
            'shunt_mode'    =>  $shunt_mode,
        ],0);

        if(!$rule->save(0)) {
            return $this->renderErrJSON('数据保存失败, 请联系管理员~~');
        }

        return $this->renderJSON([], '保存成功');
    }
}
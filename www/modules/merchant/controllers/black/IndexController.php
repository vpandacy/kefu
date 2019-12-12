<?php
namespace www\modules\merchant\controllers\black;

use common\components\DataHelper;
use common\components\helper\ValidateHelper;
use common\models\merchant\BlackList;
use common\models\uc\Staff;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

class IndexController extends BaseController
{
    /**
     * 黑名单列表
     * @return string
     */
    public function actionIndex()
    {
        if($this->isGet()) {
            return $this->render('index');
        }

        $page = intval($this->post('page',1));

        $query = BlackList::find()->where([
            'status'=>ConstantService::$default_status_true,
            'merchant_id'=>$this->getMerchantId()
        ]);

        $count = $query->count();

        $lists = $query->limit($this->page_size)
            ->offset(($page - 1) * $this->page_size)
            ->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        if($lists) {
            $staffs = DataHelper::getDicByRelateID($lists, Staff::className(), 'staff_id', 'id');

            foreach($lists as $key=>$blacklist) {
                $blacklist['staff_name'] = isset($staffs[$blacklist['staff_id']])
                    ? $staffs[$blacklist['staff_id']]['name']
                    : '暂无人员';

                $lists[$key] = $blacklist;
            }
        }

        // 转义字符.
        return $this->renderPageJSON(DataHelper::encodeArray($lists), '获取成功', $count);
    }

    /**
     * 添加黑名单.
     * @return string|\yii\web\Response
     */
    public function actionEdit()
    {
        $staff = Staff::find()
            ->where(['merchant_id'=>$this->getMerchantId(),'status'=>ConstantService::$default_status_true])
            ->asArray()
            ->all();

        return $this->render('edit',[
            'staff' =>  $staff,
        ]);
    }

    /**
     * 保存信息.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionSave()
    {
        $ip = $this->post('ip','');
        $visitor_id = $this->post('visitor_id','');
        $staff_id = $this->post('staff_id',0);
        $expired_time = $this->post('expired_time','');

        if(!ValidateHelper::validIsEmpty($ip)) {
            return $this->renderErrJSON( '请输入正确的IP地址~~' );
        }

        if(!ValidateHelper::validIsEmpty($visitor_id)) {
            return $this->renderErrJSON( '请输入正确的游客编号~~' );
        }

        if(!ValidateHelper::validIsEmpty($staff_id)) {
            return $this->renderErrJSON( '请选择正确的接待客服~~' );
        }

        if(!preg_match('/^\d{4}\-\d{2}\-\d{2}\ \d{2}\:\d{2}:\d{2}$/',$expired_time)) {
            return $this->renderJSON( '请选择正确的时间~~' );
        }

        // 开始保存信息.
        $blacklist = new BlackList();
        $blacklist->setAttributes([
            'visitor_id'    =>  $visitor_id,
            'staff_id'      =>  $staff_id,
            'expired_time'  =>  $expired_time,
            'merchant_id'   =>  $this->getMerchantId(),
            'ip'            =>  $ip,
            'status'        =>  ConstantService::$default_status_true
        ],0);

        if(!$blacklist->save(0)) {
            return $this->renderJSON( '数据保存失败, 请联系管理员~~' );
        }

        return $this->renderJSON([],'保存成功');
    }

    /**
     * 禁用.
     */
    public function actionDisable()
    {
        $id = $this->post('id',0);
        if(!$id || !is_numeric($id)) {
            return $this->renderErrJSON( '请选择正确的黑名单列表' );
        }

        $blacklist = BlackList::findOne(['id'=>$id,'merchant_id'=>$this->getMerchantId()]);

        if($blacklist['status'] != ConstantService::$default_status_true) {
            return $this->renderErrJSON( '该黑名单已经被删除了,不允许删除' );
        }

        $blacklist['status'] = ConstantService::$default_status_false;
        if(!$blacklist->save(0)) {
            return $this->renderErrJSON( '操作失败,请联系管理员' );
        }

        return $this->renderJSON([],'操作成功');
    }
}
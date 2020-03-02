<?php
namespace uc\controllers;

use common\components\helper\ModelHelper;
use common\components\ip\IPDBQuery;
use common\models\logs\CsLoginLogs;
use common\models\uc\Staff;
use uc\controllers\common\BaseController;

class LogController extends BaseController
{
    public function actionIndex()
    {
        if($this->isGet()) {
            $staffs = Staff::find()
                ->where([
                    'merchant_id'=>$this->getMerchantId()
                ])
                ->asArray()
                ->all();

            return $this->render('index', [
                'staffs'    =>  $staffs,
                'search_conditions' =>  [
                    'staff_id'  =>  intval($this->get('staff_id',0))
                ]
            ]);
        }

        // 这里添加一些信息用来保存的.
        $page = intval($this->post('page',1));

        $staff_id = intval($this->post('staff_id',0));

        $query = CsLoginLogs::find()->where(['merchant_id'=>$this->getMerchantId()]);

        if($staff_id) {
            $query->andWhere(['staff_id'=>$staff_id]);
        }

        $total = $query->count();

        $logs = $query->asArray()
            ->limit($this->page_size)
            ->offset(($page - 1) * $this->page_size)
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        if($logs) {
            $staffs = ModelHelper::getDicByRelateID($logs, Staff::className(),'staff_id','id',['mobile','name']);

            foreach($logs as $key => $log) {
                $logs[$key]['mobile'] = isset($staffs[$log['staff_id']])
                    ? $staffs[$log['staff_id']]['mobile']
                    : '暂无手机号';

                $logs[$key]['staff_name'] = isset($staffs[$log['staff_id']])
                    ? $staffs[$log['staff_id']]['name']
                    : '暂无';

                $logs[$key]['address'] = IPDBQuery::find($log['login_ip']);
            }
        }

        return $this->renderPageJSON($logs,'获取成功', $total);
    }
}
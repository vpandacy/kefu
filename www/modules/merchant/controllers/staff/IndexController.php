<?php

namespace www\modules\merchant\controllers\staff;

use common\components\DataHelper;
use common\models\Departments;
use common\models\Employees;
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
     * 获取字段信息
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionList()
    {
        $page = intval($this->get('page',1));

        $query = Employees::find();

        $count = $query->count();

        $employees = $query->limit($this->page_size)
            ->offset(($page - 1) * $this->page_size)
            ->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        if($employees) {
            $departments = DataHelper::getDicByRelateID($employees, Departments::className(), 'department_id', 'id');

            foreach($employees as $key=>$employee) {
                $employee['department'] = isset($departments[$employee['department_id']])
                    ? $departments[$employee['department_id']]['name']
                    : '暂无部门';

                $employees[$key] = $employee;
            }
        }

        return $this->renderPageJSON($employees, '获取成功', $count);
    }

    public function actionEdit()
    {
        $employee_id = intval($this->get('employee_id',0));

        if($employee_id) {
            
        }

        return $this->render('edit',[

        ]);
    }
}

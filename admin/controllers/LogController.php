<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/12/17
 * Time: 19:46
 */

namespace admin\controllers;

use admin\controllers\common\BaseController;
use common\components\helper\DateHelper;
use common\components\helper\UtilHelper;
use common\models\logs\AppAccessLog;
use common\models\logs\AppErrLogs;
use common\models\uc\QueueSms;

class LogController extends BaseController
{

    public function actionIndex()
    {
        $p = $this->get("p", 1);
        $p = ($p > 0) ? $p : 1;
        $query = AppAccessLog::find();

        $offset = ($p - 1) * $this->page_size;

        $pages = UtilHelper::ipagination([
            'total_count' => $query->count(),
            'page_size' => $this->page_size,
            'page' => $p,
            'display' => 10
        ]);

        $list = $query->orderBy([ 'id' => SORT_DESC ])
            ->offset($offset)
            ->limit($this->page_size)
            ->all();

        return $this->render("index", [
            "list" => $list,
            "pages" => $pages
        ]);
    }

    public function actionError()
    {
        $date_from = $this->get("date_from",DateHelper::getFormatDateTime("Y-m-d"));
        $date_to = $this->get("date_to",DateHelper::getFormatDateTime("Y-m-d"));
        $app_name = trim( $this->get("app_name", '') );

        $p = $this->get("p", 1);
        $p = ($p > 0) ? $p : 1;
        $query = AppErrLogs::find();

        if( $date_from && $date_to ){
            $query->andWhere([ "between","created_time", $date_from." 00:00:00",$date_to." 23:59:59" ]);
        }

        if ($app_name){
            $query->andWhere(['app_name'=>$app_name]);
        }
        $offset = ($p - 1) * $this->page_size;

        $pages = UtilHelper::ipagination([
            'total_count' => $query->count(),
            'page_size' => $this->page_size,
            'page' => $p,
            'display' => 10
        ]);

        $list = $query->orderBy([ 'id' => SORT_DESC ])
            ->offset($offset)
            ->limit($this->page_size)
            ->all();

        $search_conditions = [
            "date_from" => $date_from,
            "date_to" => $date_to,
            "app_name" => $app_name,
        ];


        return $this->render("error", [
            "list" => $list,
            "pages" => $pages,
            "search_conditions" => $search_conditions,
        ]);
    }

    public function actionSms()
    {
        $p = $this->get("p", 1);
        $p = ($p > 0) ? $p : 1;
        $query = QueueSms::find();

        $offset = ($p - 1) * $this->page_size;

        $pages = UtilHelper::ipagination([
            'total_count' => $query->count(),
            'page_size' => $this->page_size,
            'page' => $p,
            'display' => 10
        ]);

        $list = $query->orderBy([ 'id' => SORT_DESC ])
            ->offset($offset)
            ->limit($this->page_size)
            ->all();

        return $this->render("sms", [
            "list" => $list,
            "pages" => $pages
        ]);
    }

}
<?php
namespace www\modules\merchant\controllers\message;

use common\components\helper\DataHelper;
use common\components\helper\DateHelper;
use common\components\helper\UtilHelper;
use common\components\helper\ValidateHelper;
use common\models\merchant\GroupChat;
use common\models\merchant\GuestChatLog;
use common\models\merchant\GuestHistoryLog;
use common\models\uc\Staff;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

class MessageController extends BaseController
{

    public function actionIndex()
    {
        $p = $this->get("p", 1);
        $p = ($p > 0) ? $p : 1;
        $offset = ($p - 1) * $this->page_size;
        $date_from = $this->get("date_from", DateHelper::getFormatDateTime("Y-m-d 00:00" ));
        $date_to = $this->get("date_to", DateHelper::getFormatDateTime("Y-m-d H:i"));
        $staff_id = intval($this->get('staff_id',ConstantService::$default_status_false));
        $group_id = intval( $this->get('group_id',ConstantService::$default_status_neg_99 ) );
        $has_mobile = intval( $this->get("has_mobile",ConstantService::$default_status_false));
        $has_email = intval( $this->get("has_email",ConstantService::$default_status_false));
        $has_talked = intval( $this->get("has_talked",ConstantService::$default_status_neg_99));
        $kw = trim( $this->get("kw","") );

        $query = GuestHistoryLog::find()->where([ "merchant_id"=> $this->getMerchantId() ]);
        $query = $query->andWhere([ "between", "created_time",$date_from.":00",$date_to.":59" ]);

        if( $group_id > ConstantService::$default_status_neg_99 ){
            $query = $query->andWhere([ "chat_stype_id" => $group_id ]);
        }

        if( $staff_id ){
            $query = $query->andWhere([ "cs_id" => $staff_id ]);
        }

        if( $has_mobile ){
            $query = $query->andWhere([ "has_mobile" => $has_mobile ]);
        }

        if( $has_email ){
            $query = $query->andWhere([ "has_email" => $has_email ]);
        }

        if( $has_talked > ConstantService::$default_status_neg_99 ){
            $query = $query->andWhere([ "has_talked" => $has_talked ]);
        }

        if( $kw ){
            if( ValidateHelper::validUrl( $kw ) ){
                $where_land_url = [ 'LIKE','land_url','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
                $query = $query->andWhere( $where_land_url );
            }elseif ( ValidateHelper::validIp( $kw ) ){
                $query = $query->andWhere( [ "client_ip" => $kw ] );
            }else{
                $where_kw = [ 'LIKE','keyword','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
                $query = $query->andWhere( $where_kw );
            }
        }

        $pages = UtilHelper::ipagination([
            'total_count' => $query->count(),
            'page_size' => $this->page_size,
            'page' => $p,
            'display' => 10
        ]);

        $list = $query->orderBy([ 'id' => SORT_DESC ])
            ->offset($offset)
            ->limit($this->page_size)
            ->asArray()->all();
        $data = [];
        $staff_map = Staff::find()->where([ 'merchant_id' => $this->getMerchantId() ])
            ->indexBy("id")->asArray()->all();
        $style_map = GroupChat::find()->select([ "id","title" ])
            ->where([ 'merchant_id' => $this->getMerchantId() ])
            ->indexBy("id")->asArray()->all();

        $style_map[ ConstantService::$default_status_false ] = [
            "id" => ConstantService::$default_status_false,
            "title" => "默认风格"
        ];

        if( $list ) {
            foreach($list as $_item ) {
                $tmp_staff_info = $staff_map[ $_item['cs_id'] ]??[];
                $tmp_style_info = $style_map[ $_item['chat_stype_id'] ]??[];
                $tmp_data = [
                    "guest_number" => DataHelper::getGuestNumber( $_item['uuid']),
                    "uuid" => $_item['uuid'],
                    "client_ip" => $_item['client_ip'],
                    "staff_info" => $tmp_staff_info,
                    "style_info" => $tmp_style_info,
                    "referer_url" => $_item['referer_url'],
                    "land_url" => $_item['land_url'],
                    "source_desc" => ConstantService::$guest_source[ $_item['source'] ],
                    "duration" => $_item['chat_duration'],
                    "duration_desc" => DateHelper::getPrettyDuration(abs($_item['chat_duration'])),
                    "created_time" => $_item['created_time'],
                ];
                $data[] = $tmp_data;
            }
        }
        $sc = [
            "p" => $p,
            "date_from" => $date_from,
            "date_to" => $date_to,
            "staff_id" => $staff_id,
            "group_id" => $group_id,
            "kw" => $kw,
            "has_talked" => $has_talked,
            "has_mobile" => $has_mobile,
            "has_email" => $has_email,
        ];
        return $this->render("index",[
            "list" => $data,
            "staff_map" => $staff_map,
            "style_map" => $style_map,
            "sc" => $sc,
            "pages" => $pages
        ]);
    }

    public function actionInfo(){
        $uuid = trim( $this->get("uuid","") );
        $date_from = $this->get("date_from", DateHelper::getFormatDateTime("Y-m-d 00:00" ));
        $date_to = $this->get("date_to", DateHelper::getFormatDateTime("Y-m-d H:i"));
        if( !$uuid ){
            return $this->renderErrJSON( "获取详情失败~~" );
        }

        $query = GuestHistoryLog::find()
            ->where([ "merchant_id"=> $this->getMerchantId() ])
            ->andWhere([ "uuid" => $uuid ]);

        $query = $query->andWhere([ "between", "created_time",$date_from.":00",$date_to.":59" ]);

        $list = $query->orderBy([ 'id' => SORT_DESC ])->asArray()->all();
        $data = [];
        if( $list ){
            foreach ( $list as $_item ){
                $data[] = [
                    "id" => $_item['id'],
                    "created_time" => str_replace(" ","<br/>",$_item['created_time'])
                ];
            }
        }

        return $this->renderPopView("info",[
            "list" => $data
        ]);
    }

    public function actionChat(){
        $guest_log_id = $this->get("id",0);
        $data = [];
        if( $guest_log_id ){
            $data = GuestChatLog::find()
                ->where([ "merchant_id" => $this->getMerchantId() ])
                ->andWhere([ "guest_log_id" => $guest_log_id ])
                ->orderBy([ "id" => SORT_ASC ])
                ->asArray()->all();
        }
        return $this->renderPopView("chat",[
            "list" => $data
        ]);
    }

    public function actionLog(){
        $guest_log_id = $this->get("id", 0);
        $info = [];
        if( $guest_log_id ){
            $info = GuestHistoryLog::find()
                ->where([ "merchant_id" => $this->getMerchantId() ])
                ->andWhere([ "id" => $guest_log_id ])
                ->one();
        }

        return $this->renderPopView("log",[
            "info" => $info
        ]);
    }
    public function actionTrace(){
        $guest_log_id = $this->get("id", 0);
        $info = [];
        if( $guest_log_id ){
            $info = GuestHistoryLog::find()
                ->where([ "merchant_id" => $this->getMerchantId() ])
                ->andWhere([ "id" => $guest_log_id ])
                ->one();
        }

        return $this->renderPopView("trace",[
            "info" => $info
        ]);
    }

}
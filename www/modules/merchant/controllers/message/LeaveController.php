<?php

namespace www\modules\merchant\controllers\message;

use common\components\DataHelper;
use common\components\helper\DateHelper;
use common\components\helper\ModelHelper;
use common\components\helper\UtilHelper;
use common\models\merchant\GroupChat;
use common\models\merchant\LeaveMessage;
use common\services\ConstantService;
use www\modules\merchant\controllers\common\BaseController;

class LeaveController extends BaseController
{
    /**
     * 留言表单
     * @return string
     */
    public function actionIndex()
    {

        $this->page_size = 30;
        $p = $this->get("p", 1);
        $p = ($p > 0) ? $p : 1;
        $offset = ($p - 1) * $this->page_size;
        $date_from = $this->get("date_from", DateHelper::getFormatDateTime("Y-m-d"));
        $date_to = $this->get("date_to", DateHelper::getFormatDateTime("Y-m-d"));
        $group_id = intval( $this->get('group_id',ConstantService::$default_status_neg_99 ) );
        $kw = trim($this->get("kw", ""));

        $query = LeaveMessage::find()->where(["merchant_id" => $this->getMerchantId()]);
        $query = $query->andWhere(["between", "created_time", $date_from . " 00:00:00", $date_to . " 23:59:59"]);

        if ($kw) {
            $where_mobile = [
                'LIKE',
                'mobile',
                '%' . strtr($kw, ['%' => '\%', '_' => '\_', '\\' => '\\\\']) . '%',
                false
            ];
            $where_message = [
                'LIKE',
                'message',
                '%' . strtr($kw, ['%' => '\%', '_' => '\_', '\\' => '\\\\']) . '%',
                false
            ];
            $query = $query->andWhere(["OR", $where_mobile, $where_message]);
        }

        if( $group_id > ConstantService::$default_status_neg_99 ){
            $query = $query->andWhere([ "group_chat_id" => $group_id ]);
        }

        $pages = UtilHelper::ipagination([
            'total_count' => $query->count(),
            'page_size' => $this->page_size,
            'page' => $p,
            'display' => 10
        ]);

        $list = $query->orderBy(['id' => SORT_DESC])
            ->offset($offset)
            ->limit($this->page_size)
            ->asArray()->all();
        $data = [];
        $style_map = GroupChat::find()->select(["id", "title"])
            ->where(['merchant_id' => $this->getMerchantId()])
            ->indexBy("id")->asArray()->all();

        $style_map[ConstantService::$default_status_false] = [
            "id" => ConstantService::$default_status_false,
            "title" => "默认风格"
        ];

        if ($list) {
            foreach ($list as $_key => $_item) {
                $tmp_style_info = $style_map[$_item['group_chat_id']] ?? [];
                $tmp_data = $_item;
                $tmp_data['style_info'] = $tmp_style_info;
                $data[] = $tmp_data;
            }
        }

        $sc = [
            "p" => $p,
            "date_from" => $date_from,
            "date_to" => $date_to,
            "kw" => $kw,
            "group_id" => $group_id,
        ];
        return $this->render("index", [
            "list" => $data,
            "style_map" => $style_map,
            "sc" => $sc,
            "pages" => $pages
        ]);
    }

    /**
     * 信息保存.
     */
    public function actionOps()
    {
        $id = intval($this->post('id', 0));

        if (!$id) {
            return $this->renderErrJSON(ConstantService::$default_sys_err);
        }

        $info = LeaveMessage::findOne(['id' => $id, 'merchant_id' => $this->getMerchantId()]);

        if ( !$info || $info['status']) {
            return $this->renderErrJSON(ConstantService::$default_sys_err );
        }

        $info->setAttribute('status', ConstantService::$default_status_true);
        $info->save(0);
        return $this->renderJSON([], '标记处理成功~~');
    }
}
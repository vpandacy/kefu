<?php
namespace www\modules\merchant\controllers\setting;

use common\models\merchant\CommonWord;
use common\services\CommonConstant;
use common\services\ConstantService;
use common\services\office\ExcelService;
use www\modules\merchant\controllers\common\BaseController;

class WordController extends BaseController {

    public function actionImport(){
        $step = intval($this->get("step", 1));
        if ($step == 1) {
            return $this->render("step", ["step" => $step]);
        } elseif ($step == 2) {
            return $this->render("step", [ "step" => $step ]);
        } elseif ($step == 3) {
            $err_msg = '';
            if( !isset( $_FILES['file'] ) ){
                return $this->render('step', ['msg' => '请选择文件~~', "step" => 1]);
            }
            $file_target = $_FILES['file'];
            if (empty($file_target) || $file_target['error'] > 0 || $file_target['size'] > 1048576) {
                return $this->render('step', ['msg' => '请选择文件，仅支持excel和csv格式文件，且大小小于1M', "step" => 1]);
            }

            $file_extends = explode(".",$file_target['name']);
            $list = ExcelService::readFile($file_target['tmp_name'], $this->excel_mapping,end($file_extends) );

            return $this->render("step", [
                "step" => $step ,
                "list" => $list,
                "err_msg" =>$err_msg,
            ]);
        }
    }

    public function actionSave()
    {
        if (!$this->isPost() ) {
            return $this->renderErrJSON(CommonConstant::$default_sys_err);
        }
        $data = $this->post("data",[]);
        if( !$data ){
            return $this->renderErrJSON("没有数据提交撒 ~~");
        }
        $connection = CommonWord::getDb();
        $transaction = $connection->beginTransaction();
        try {
            $field = [ "merchant_id","title","words","status" ];
            $insert_data = [];
            foreach ( $data as $_item ){
                $insert_data[] = [
                    $this->getMerchantId(),
                    $_item['title'],
                    $_item['words'],
                    ConstantService::$default_status_true
                ];
            }
            $res = $connection->createCommand()
                ->batchInsert(CommonWord::tableName(),$field,$insert_data)
                ->execute();
            if(!$res)
            {
                $transaction->rollBack();
                throw new \Exception('导入失败~~~');
            }
            $transaction->commit();
            return $this->renderJSON( [] , "导入成功");
        }catch (\Exception $e) {
            return $this->renderErrJSON($e->getMessage());
        }
    }

    private $excel_mapping = [
        "title" => [ "标题" ],
        "words" => [ "内容" ]
    ];
}
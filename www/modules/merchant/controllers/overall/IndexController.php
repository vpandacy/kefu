<?php
namespace www\modules\merchant\controllers\overall;

use common\models\merchant\CommonWord;
use common\services\ConstantService;
use common\services\ExcelService;
use www\modules\merchant\controllers\common\BaseController;
use Yii;

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
     * 获取常用语言的数据.
     */
    public function actionList()
    {
        $page = intval($this->get('page',1));

        $query = CommonWord::find();

        $count = $query->count();

        $data = $query->where(['merchant_id'=>$this->getMerchantId()])
            ->limit($this->page_size)
            ->offset($this->page_size * ($page - 1))
            ->orderBy(['id'=>SORT_DESC])
            ->asArray()
            ->all();

        // 根据layui的分页来处理.
        return $this->renderPageJSON($data,'获取成功', $count);
    }

    /**
     * 编辑或添加界面.
     * @return string|\yii\web\Response
     */
    public function actionEdit()
    {
        $word_id = intval($this->get('word_id',0));

        $words = $word_id
            ? CommonWord::findOne(['id'=>$word_id, 'merchant_id'=>$this->getMerchantId(),'status'=>ConstantService::$default_status_true])
            : new CommonWord();

        if($word_id && !$words) {
            // 返回回去.
            return $this->responseFail('您暂无权限操作此界面');
        }

        return $this->render('edit',[
            'words' =>  $words,
        ]);
    }


    /**
     * 信息保存.
     */
    public function actionSave()
    {
        $data = $this->post(null);

        $request_r = ['id','words'];

        if(count(array_intersect(array_keys($data), $request_r)) != count($request_r)) {
            return $this->renderJSON([],'参数丢失', ConstantService::$response_code_fail);
        }

        if(!$data['words'] || mb_strlen($data['words']) > 255) {
            return $this->renderJSON([],'请输入正确的姓名/商户名', ConstantService::$response_code_fail);
        }

        $words = $data['id'] > 0
            ? CommonWord::findOne(['id'=>$data['id'],'merchant_id'=>$this->getMerchantId(),'status'=>ConstantService::$default_status_true])
            : new CommonWord();

        if($data['id'] > 0 && !$words['id']) {
            return $this->renderJSON([],'非法的员工', ConstantService::$response_code_fail);
        }

        if(!$data['id']) {
            $data['merchant_id'] = $this->getMerchantId();
            $data['status'] = ConstantService::$default_status_true;
        }

        $words->setAttributes($data,0);

        if(!$words->save(0)) {
            return $this->renderJSON([],'数据库保存失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'操作成功', ConstantService::$response_code_success);
    }


    /**
     * 恢复.
     */
    public function actionRecover()
    {
        $ids = $this->post('ids');

        if(!count($ids)) {
            return $this->renderJSON([],'请选择需要恢复的常用语', ConstantService::$response_code_fail);
        }

        if(!CommonWord::updateAll(['status'=>ConstantService::$default_status_true],['id'=>$ids,'merchant_id'=>$this->getMerchantId()])) {
            return $this->renderJSON([],'恢复失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'恢复成功', ConstantService::$response_code_success);
    }


    /**
     * 禁用.
     */
    public function actionDisable()
    {
        $id = $this->post('id',0);
        if(!$id || !is_numeric($id)) {
            return $this->renderJSON([],'请选择正确的常用语', ConstantService::$response_code_fail);
        }

        $words = CommonWord::findOne(['id'=>$id,'merchant_id'=>$this->getMerchantId()]);

        if($words['status'] != ConstantService::$default_status_true) {
            return $this->renderJSON([],'该常用语已经被禁止使用了', ConstantService::$response_code_fail);
        }

        $words['status'] = 0;
        if(!$words->save(0)) {
            return $this->renderJSON([],'操作失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'操作成功', ConstantService::$response_code_success);
    }

    /**
     * 获取文件.
     * @return string
     */
    public function actionImport()
    {
        if(Yii::$app->request->isGet) {
            return $this->render('import');
        }

        $data = ExcelService::import('file');

        if(!$data) {
            return $this->renderJSON([],ExcelService::getLastErrorMsg(), ConstantService::$response_code_fail);
        }

        if(count($data) <= 1) {
            return $this->renderJSON([],'请填写对应的Excel内容', ConstantService::$response_code_fail);
        }

        // 去除第一个.
        array_shift($data);
        $insert_data = [];

        foreach($data as $row) {
            if(!$row[0]) {
                return $this->renderJSON([],'导入格式错误,请填写完整的常用语', ConstantService::$response_code_fail);
            }

            array_push($insert_data, [
                'words' =>  $row[0],
                'status'=>  $row[1],
                'merchant_id'   =>  $this->getMerchantId(),
            ]);
        }

        $ret = CommonWord::getDb()->createCommand()
            ->batchInsert(CommonWord::tableName(),['words','status','merchant_id'], $insert_data)
            ->execute();

        if(!$ret) {
            return $this->renderJSON([],'数据导入失败,请联系管理员', ConstantService::$response_code_fail);
        }

        return $this->renderJSON([],'数据导入成功', ConstantService::$response_code_success);

    }
}
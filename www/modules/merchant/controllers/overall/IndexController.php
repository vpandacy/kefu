<?php
namespace www\modules\merchant\controllers\overall;

use common\components\helper\ValidateHelper;
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
        if($this->isGet()) {
            return $this->render('index');
        }

        $page = intval($this->post('page',1));

        $query = CommonWord::find()->where(['merchant_id'=>$this->getMerchantId()]);

        $count = $query->count();

        $data = $query->limit($this->page_size)
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
            return $this->renderErrJSON( '参数丢失~~' );
        }

        if(!ValidateHelper::validLength($data['words'],1,255)) {
            return $this->renderErrJSON('请输入正确的姓名/商户名' );
        }

        $words = $data['id'] > 0
            ? CommonWord::findOne(['id'=>$data['id'],'merchant_id'=>$this->getMerchantId(),'status'=>ConstantService::$default_status_true])
            : new CommonWord();

        if($data['id'] > 0 && !$words['id']) {
            return $this->renderErrJSON( '非法的员工~~' );
        }

        if(!$data['id']) {
            $data['merchant_id'] = $this->getMerchantId();
            $data['status'] = ConstantService::$default_status_true;
        }

        $words->setAttributes($data,0);

        if(!$words->save(0)) {
            return $this->renderErrJSON( '数据库保存失败,请联系管理员' );
        }

        return $this->renderJSON([],'操作成功');
    }


    /**
     * 恢复.
     */
    public function actionRecover()
    {
        $ids = $this->post('ids');

        if(!count($ids)) {
            return $this->renderErrJSON( '请选择需要恢复的常用语' );
        }

        if(!CommonWord::updateAll(['status'=>ConstantService::$default_status_true],['id'=>$ids,'merchant_id'=>$this->getMerchantId()])) {
            return $this->renderErrJSON('恢复失败,请联系管理员' );
        }

        return $this->renderJSON([],'恢复成功');
    }


    /**
     * 禁用.
     */
    public function actionDisable()
    {
        $id = $this->post('id',0);
        if(!$id || !is_numeric($id)) {
            return $this->renderErrJSON('请选择正确的常用语' );
        }

        $words = CommonWord::findOne(['id'=>$id,'merchant_id'=>$this->getMerchantId()]);

        if($words['status'] != ConstantService::$default_status_true) {
            return $this->renderErrJSON( '该常用语已经被禁止使用了' );
        }

        $words['status'] = 0;
        if(!$words->save(0)) {
            return $this->renderErrJSON( '操作失败,请联系管理员' );
        }

        return $this->renderJSON([],'操作成功');
    }

    /**
     * 获取文件.
     * @return string
     */
    public function actionImport()
    {
        if($this->isGet()) {
            return $this->render('import');
        }

        $data = ExcelService::import('file');

        if(!$data) {
            return $this->renderErrJSON( ExcelService::getLastErrorMsg() );
        }

        if(count($data) <= 1) {
            return $this->renderErrJSON( '请填写对应的Excel内容' );
        }

        // 去除第一个.
        array_shift($data);
        $insert_data = [];

        foreach($data as $row) {
            if(!$row[0]) {
                return $this->renderErrJSON( '导入格式错误,请填写完整的常用语' );
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
            return $this->renderErrJSON( '数据导入失败,请联系管理员' );
        }

        return $this->renderJSON([],'数据导入成功');

    }
}
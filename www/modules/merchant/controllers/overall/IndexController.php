<?php
namespace www\modules\merchant\controllers\overall;

use common\models\merchant\CommonWord;
use common\services\ConstantService;
use common\services\GlobalUrlService;
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
     * 获取常用语言的数据.
     */
    public function actionList()
    {
        $page = intval($this->get('page',1));

        $page_size = $this->page_size;

        $query = CommonWord::find();

        $count = $query->count();

        $data = $query->where(['merchant_id'=>$this->getMerchantId()])
            ->limit($this->page_size)
            ->offset($page_size * ($page - 1))
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
            return $this->redirect(GlobalUrlService::buildMerchantUrl('/overall/index/index'));
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
}
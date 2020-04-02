<?php
namespace www\modules\merchant\controllers\user;

use common\components\helper\DataHelper;
use common\components\helper\ModelHelper;
use common\components\helper\ValidateHelper;
use common\models\merchant\GroupChat;
use common\models\merchant\Member;
use common\models\uc\Staff;
use common\services\AreaService;
use common\services\GlobalUrlService;
use www\modules\merchant\controllers\common\BaseController;

class IndexController extends BaseController
{
    public function actionIndex()
    {
        if($this->isGet()) {
            return $this->render('index');
        }

        $page = intval($this->post('page',1));

        $query = Member::find()->where([
            'merchant_id'=>$this->getMerchantId()
        ]);

        $count = $query->count();

        $lists = $query->limit($this->page_size)
            ->offset(($page - 1) * $this->page_size)
            ->asArray()
            ->orderBy(['id'=>SORT_DESC])
            ->all();

        if($lists) {
            $staffs = ModelHelper::getDicByRelateID($lists, Staff::className(), 'cs_id', 'id',['name']);
            $style = ModelHelper::getDicByRelateID($lists, GroupChat::className(), 'chat_style_id','id',['title']);

            foreach($lists as $key=>$member) {
                $member['staff_name'] = isset($staffs[$member['cs_id']])
                    ? $staffs[$member['cs_id']]['name']
                    : '暂无人员';

                $member['style_title']= isset($style[$member['chat_style_id']])
                    ? $style[$member['chat_style_id']]['title']
                    : '普通风格';

                $lists[$key] = $member;
            }
        }

        // 转义字符.
        return $this->renderPageJSON(DataHelper::encodeArray($lists), '获取成功', $count);
    }

    /**
     * 保存用户信息.
     */
    public function actionEdit()
    {
        if($this->isGet()) {
            $member_id = $this->get('member_id',0);
            if(!$member_id) {
                return $this->redirect(GlobalUrlService::buildUcUrl('/default/forbidden'));
            }

            $member = Member::findOne(['id'=>$member_id,'merchant_id'=>$this->getMerchantId()]);

            if(!$member) {
                return $this->redirect(GlobalUrlService::buildUcUrl('/default/forbidden'));
            }

            $provinces = AreaService::getProvinceMapping();

            $province_id = $member['province_id'] ? $member['province_id'] : array_keys($provinces)[0];

            $cities = AreaService::getProvinceCityTree($province_id);

            return $this->render('edit',[
                'member'    =>  $member,
                'provinces' =>  $provinces,
                'current_province'  =>  $province_id,
                'cities'    =>  $cities['city']
            ]);
        }


        $name = $this->post('name',''); // 姓名.
        $mobile = $this->post('mobile',''); // 手机号.
        $email = $this->post('email',''); // 邮件.
        $qq = $this->post('qq',''); // QQ号码.
        $wechat = $this->post('wechat',''); // 微信号.
        $desc = $this->post('desc',''); // 描述.
        $province_id = $this->post('province_id',0);
        $city_id = $this->post('city_id',0);
        $id = $this->post('id',0);

        if($name && !ValidateHelper::validLength($name,1,255)) {
            return $this->renderErrJSON('请输入正确长度的名称');
        }

        if($qq && !ValidateHelper::validLength($qq,1,13)) {
            return $this->renderErrJSON('请输入正确长度的ＱＱ号');
        }

        if($mobile && !ValidateHelper::validMobile($mobile)) {
            return $this->renderErrJSON('请输入正确的手机号');
        }

        if($email && !ValidateHelper::validEmail($email)) {
            return $this->renderErrJSON('请输入正确格式的邮箱');
        }

        if($wechat && !ValidateHelper::validLength($wechat,1,255)) {
            return $this->renderErrJSON('请填写正确的微信号长度');
        }

        if($desc && !ValidateHelper::validLength($desc,1,255)) {
            return $this->renderErrJSON('请填写正确长度的备注');
        }

        // 开始保存信息.
        $member = Member::findOne(['id'=>$id]);
        if(!$member) {
            return $this->renderErrJSON('暂未找到该会员');
        }

        $member->setAttributes([
            'merchant_id'   => $this->getMerchantId(),
            'name'  => $name,
            'mobile'=> $mobile,
            'email' => $email,
            'qq'    => $qq,
            'wechat'=> $wechat,
            'desc'  => $desc,
            'province_id'   =>  $province_id,
            'city_id'   =>  $city_id
        ]);


        if(!$member->save(0)) {
            return $this->renderErrJSON('数据保存失败，请联系管理员');
        }

        return $this->renderJSON('保存成功');
    }


    /**
     * 获取选择城市的代码.
     * @return \yii\console\Response|\yii\web\Response
     */
    public function actionProvince()
    {
        $province_id = $this->post('province_id');

        $cities = AreaService::getProvinceCityTree($province_id);

        if(!$cities) {
            return $this->renderErrJSON('请选择正确的城市');
        }

        return $this->renderJSON($cities['city'],'获取成功');
    }
}
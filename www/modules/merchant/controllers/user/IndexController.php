<?php
namespace www\modules\merchant\controllers\user;

use common\components\helper\DataHelper;
use common\components\helper\ModelHelper;
use common\components\helper\UtilHelper;
use common\components\helper\ValidateHelper;
use common\models\merchant\GroupChat;
use common\models\merchant\Member;
use common\models\uc\Staff;
use common\services\AreaService;
use common\services\ConstantService;
use common\services\GlobalUrlService;
use www\modules\merchant\controllers\common\BaseController;

class IndexController extends BaseController
{
    public function actionIndex()
    {
        $p = $this->get("p", 1);
        $p = ($p > 0) ? $p : 1;
        $offset = ($p - 1) * $this->page_size;
        $kw = trim( $this->get("kw","") );

        $query = Member::find()->where([
            'merchant_id'=>$this->getMerchantId()
        ]);
        if( $kw ){
            $where_mobile = [ 'LIKE','mobile','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $where_name = [ 'LIKE','name','%'.strtr($kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $query = $query->andWhere( [ "OR",$where_mobile,$where_name ] );
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
        if( $list ) {
            $staff_map = ModelHelper::getDicByRelateID($list, Staff::class, 'cs_id', 'id',[ 'name' ]);
            $style_map = ModelHelper::getDicByRelateID($list, GroupChat::class, 'chat_style_id','id',[ 'title' ]);

            foreach( $list as  $_member) {
                $tmp_staff_info = $staff_map[ $_member['cs_id'] ]??[];
                $tmp_style_info = $style_map[ $_member['chat_style_id'] ]??[];
                $tmp_data = [
                    "id" => $_member['id'],
                    "name" => $_member['name'],
                    "mobile" => $_member['mobile'],
                    "email" => $_member['email'],
                    "qq" => $_member['qq'],
                    "wechat" => $_member['wechat'],
                    "reg_ip" => $_member['reg_ip'],
                    "source" => $_member['source'],
                    "source_desc" => ConstantService::$guest_source[ $_member['source'] ]??'',
                    "created_time" => $_member['created_time'],
                    "staff_name" => $tmp_staff_info['name']??'',
                    "style" => $tmp_style_info['title']??'默认风格',
                ];
                $data[] = $tmp_data;
            }
        }
        return $this->render("index",[
            "list" => $data,
            "sc" => [ "kw" => $kw ],
            "pages" => $pages
        ]);
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
<?php
namespace common\services\uc;

use common\components\helper\DateHelper;
use common\models\merchant\BlackList;
use common\models\merchant\GroupChat;
use common\models\merchant\GroupChatSetting;
use common\models\merchant\ReceptionRule;
use common\models\uc\Merchant;
use common\models\uc\MerchantSetting;
use common\models\uc\Staff;
use common\services\BaseService;
use common\services\CommonService;
use common\services\ConstantService;
use common\services\redis\CacheService;

class MerchantService extends BaseService
{
    public static function checkValid( $saas_merchant_id = 0 ){
        $info = self::getInfoById( $saas_merchant_id );
        if( !$info ){
            return self::_err("商户信息未找到~~");
        }

//        $now = DateHelper::getFormatDateTime( "Y-m-d" );
//        if( $now > $info['valid_to'] ){
//            return self::_err("非常抱歉，系统使用期限已过,请联系管理员~~");
//        }

        return $info;
    }

    /**
     * 保存商户信息.
     * @param int $saas_merchant_id
     * @return bool|mixed
     */
    public static function getInfoById( $saas_merchant_id = 0 ){
        if( !$saas_merchant_id ){
            return false;
        }
        $cache_key = "merchant_{$saas_merchant_id}";
        $data = CacheService::get( $cache_key );
        if( !$data ) {
            $info = Merchant::find()
                ->where([ 'id'=> $saas_merchant_id ] )
                ->asArray()
                ->one();

            $data = json_encode( $info?:[] );
            CacheService::set($cache_key,$data,86400 * 30 );
        }
        return json_decode( $data,true );
    }

    /**
     * 获取商户信息.
     * @param null $sn
     * @return bool|mixed
     */
    public static function getInfoBySn( $sn = null ) {
        if( !$sn ){
            return false;
        }

        $cache_key = "merchant_{$sn}";
        $data = CacheService::get( $cache_key );

        if( !$data ) {
            $info = Merchant::find()
                ->where([ 'sn'=> $sn,'status'=>ConstantService::$default_status_true])
                ->asArray()
                ->one();
            $data = json_encode( $info?:[] );
            CacheService::set($cache_key, $data,86400 * 30 );
        }

        return json_decode( $data,true );
    }

    /**
     * 创建一个商户.
     * @param int $app_id
     * @param $merchant_name
     * @param $mobile
     * @param $password
     * @return bool
     */
    public static function createMerchant($app_id, $merchant_name,$mobile,$password)
    {
        $merchant = new Merchant();
        $now = DateHelper::getFormatDateTime();

        if(Staff::findOne(['mobile'=>$mobile])) {
            return self::_err('该手机号已经被别人使用了，请重新更换一个手机号');
        }

        $merchant->setAttributes([
            'status'    =>  ConstantService::$default_status_true,
            'sn'        =>  CommonService::genUniqueName(),
            'name'      =>  $merchant_name,
            'logo'      =>  ConstantService::$default_avatar,
            'app_id'    =>  $app_id,
            'created_time'  =>  $now,
            'updated_time'  =>  $now
        ],0);

        if(!$merchant->save(0)) {
            return self::_err('数据库保存失败,请联系管理员');
        }

        $employee = new Staff();
        $salt = CommonService::genUniqueName();
        $password = md5($merchant['id'] . '-' . $password  . '-' . $salt);
        $employee->setAttributes([
            'merchant_id'   =>  $merchant->primaryKey,
            'sn'            =>  CommonService::genUniqueName(),
            'app_ids'       =>  ',1,',
            'name'          =>  $merchant_name,
            'mobile'        =>  $mobile,
            'avatar'        =>  ConstantService::$default_avatar,
            'password'      =>  $password,
            'listen_nums'   =>  0,
            'status'        =>  ConstantService::$default_status_true,
            'is_root'       =>  1,
            'created_time'  =>  $now,
            'updated_time'  =>  $now,
            'salt'          =>  $salt,
        ],0);

        if(!$employee->save(0)) {
            return self::_err('数据库保存失败,请联系管理员');
        }

        return true;
    }

    /**
     * 获取商户配置．
     * @param $merchant_id
     * @return bool|mixed
     */
    public static function getConfig( $merchant_id ){
        if( !$merchant_id ){
            return false;
        }

        $cache_key = "merchant_config_{$merchant_id}";
        $data = CacheService::get( $cache_key );

        if( !$data ) {
            $config = MerchantSetting::find()
                ->where(['merchant_id' => $merchant_id])
                ->asArray()
                ->one();

            $data = json_encode( $config?:[] );
            CacheService::set($cache_key,$data,86400 * 30 );
        }
        return json_decode( $data,true );
    }

    /**
     * 更新商户信息.
     * @param int $merchant_id
     * @param int $app_id
     * @param string $logo
     * @param string $contact
     * @param string $name
     * @param string $desc
     * @return bool
     */
    public static function updateMerchant($merchant_id,$app_id,$logo,$contact,$name,$desc)
    {
        $merchant = Merchant::findOne(['id'=>$merchant_id,'app_id'=>$app_id]);

        if(!$merchant) {
            return self::_err('暂找不到对应的商户信息');
        }

        $merchant->setAttributes([
            'logo'  =>  $logo,
            'contact'   =>  $contact,
            'name'  =>  $name,
            'desc'  =>  $desc
        ],0);

        if(!$merchant->save(0)) {
            return self::_err('数据保存失败,请联系管理员' );
        }

        // 重新设置缓存信息.
        $cache_key = "merchant_{$merchant['sn']}";
        $data = json_encode($merchant->toArray());
        CacheService::set($cache_key, $data, 86400 * 30);
        $cache_key = "merchant_{$merchant['id']}";
        CacheService::set($cache_key, $data, 86400 * 30);
        return true;
    }

    /**
     * 保存商户的配置信息.
     * @param string $merchant_id
     * @param int $auto_disconnect
     * @param string $greetings
     * @return bool
     */
    public static function updateMerchantConfig($merchant_id, $auto_disconnect, $greetings)
    {
        $setting = MerchantSetting::findOne(['merchant_id'=>$merchant_id]);

        if(!$setting) {
            $setting = new MerchantSetting();
        }

        $setting->setAttributes([
            'auto_disconnect'   =>  $auto_disconnect,
            'greetings'         =>  $greetings,
            'merchant_id'       =>  $merchant_id
        ],0);

        if($setting->save(0) === false) {
            return self::_err( '数据保存失败,请联系管理员' );
        }

        $cache_key = 'merchant_config_' . $setting['merchant_id'];
        CacheService::set($cache_key, json_encode($setting->toArray()),86400 * 30);
        return true;
    }

    /**
     * 获取商户风格的设置.
     * @param $code
     * @param int $merchant_id
     * @return array|false
     */
    public static function getStyleConfig($code, $merchant_id)
    {
        $group_chat_id = 0;
        if($code) {
            $group_chat = GroupChat::findOne(['sn'=>$code,'merchant_id'=>$merchant_id]);
            if(!$group_chat) {
                return self::_err('未知的风格');
            }
            $group_chat_id = $group_chat['id'];
        }
        $cache_key = 'merchant_style_config_' . $group_chat_id;
        $style = CacheService::get($cache_key);
        if(!$style) {
            $setting = GroupChatSetting::find()
                ->asArray()
                ->where(['group_chat_id'=>$group_chat_id,'merchant_id'=>$merchant_id])
                ->one();

            if(!$setting) {
                // 生成默认的配置信息.
                $setting = self::genDefaultStyleConfig();
                $setting['group_chat_id'] = 0;
            }

            $style = json_encode($setting);
            CacheService::set($cache_key, $style, 86400 * 30);
        }

        return @json_decode($style, true);
    }

    /**
     * 生成默认的配置信息.
     * @return array
     */
    public static function genDefaultStyleConfig()
    {
        return [
            'is_history'    =>  0,
            'province_id'   =>  0,
            'is_active'     =>  1,
            'windows_status'=>  0,
            'is_force'      =>  1,
            'lazy_time'     =>  10,
            'is_show_num'   =>  1
        ];
    }

    /**
     * 更新商户风格的设置.
     * @param int $id
     * @param int $merchant_id
     * @param array $params
     * @return array|false
     */
    public static function updateStyleConfig($id, $merchant_id, $params)
    {
        // 检查对应的信息.
        $setting = GroupChatSetting::findOne([
            'group_chat_id' =>  $id,
            'merchant_id'   =>  $merchant_id,
        ]);

        if(!$setting) {
            $setting = new GroupChatSetting();
        }

        $params['merchant_id'] = $merchant_id;

        $setting->setAttributes($params,0);

        if(!$setting->save(0)) {
            return self::_err('数据保存失败');
        }

        $cache_key = 'merchant_style_config_' . $id;
        CacheService::set($cache_key, json_encode($setting->toArray()), 86400 * 30);
        return true;
    }

    /**
     * 生成默认的配置信息.
     * @return array
     */
    public static function genDefaultReceptionRuleConfig()
    {
        return [
            'reception_rule'    =>  0,
            'reception_strategy'=>  0,
            'shunt_mode'        =>  0,
            'distribution_mode' =>  0
        ];
    }

    /**
     * 更新客服分配规则的设置.
     * @param int $group_chat_id
     * @param int $merchant_id
     * @param array $params
     * @return bool
     */
    public static function updateReceptionConfig($group_chat_id, $merchant_id, $params)
    {
        // 开始进行保存.
        $rule = ReceptionRule::findOne([
            'merchant_id' => $merchant_id,
            'group_chat_id' => $group_chat_id
        ]);

        if(!$rule) {
            $rule = new ReceptionRule();
        }

        $params['merchant_id'] = $merchant_id;
        $params['group_chat_id'] = $group_chat_id;

        $rule->setAttributes($params,0);

        if(!$rule->save()) {
            return self::_err('数据保存失败，请联系管理员');
        }

        $cache_key = 'merchant_style_reception_' . $group_chat_id;
        CacheService::set($cache_key, json_encode($rule->toArray()), 86400 * 30);
        return true;
    }

    /**
     * 获取客服分配规则的设置.
     * @param $code
     * @param int $merchant_id
     * @return array|false
     */
    public static function getReceptionConfig($code, $merchant_id)
    {
        $group_chat_id = 0;
        if($code) {
            $group_chat = GroupChat::findOne(['sn'=>$code,'merchant_id'=>$merchant_id]);
            if(!$group_chat) {
                return self::_err('未知的风格');
            }
            $group_chat_id = $group_chat['id'];
        }
        $cache_key = 'merchant_style_reception_' . $group_chat_id;
        $reception = CacheService::get($cache_key);
        if(!$reception) {
            $reception = ReceptionRule::find()
                ->asArray()
                ->where(['group_chat_id'=>$group_chat_id,'merchant_id'=>$merchant_id])
                ->one();

            if(!$reception) {
                // 生成默认的配置信息.
                $reception = self::genDefaultReceptionRuleConfig();
                $reception['group_chat_id'] = 0;
            }

            $reception = json_encode($reception);
            CacheService::set($cache_key, $reception, 86400 * 30);
        }

        return @json_decode($reception, true);
    }
}
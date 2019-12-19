<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property int $id 会员id
 * @property int $merchant_id 商户id
 * @property int $cs_id 员工id
 * @property int $chat_style_id 风格分组id
 * @property string $name 姓名
 * @property string $mobile 手机号
 * @property string $email 邮箱
 * @property string $qq QQ号码
 * @property string $wechat 微信号码
 * @property string $reg_ip 注册时的ip
 * @property int $province_id 省份
 * @property int $city_id 城市
 * @property int $source 注册时的来源
 * @property string $desc 备注
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('chat_db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'cs_id', 'chat_style_id', 'province_id', 'city_id', 'source'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['name', 'mobile', 'email', 'qq', 'wechat', 'reg_ip', 'desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'cs_id' => 'Cs ID',
            'chat_style_id' => 'Chat Style ID',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'qq' => 'Qq',
            'wechat' => 'Wechat',
            'reg_ip' => 'Reg Ip',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'source' => 'Source',
            'desc' => 'Desc',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

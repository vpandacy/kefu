<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "merchants".
 *
 * @property int $id 默认主键
 * @property string $mobile 手机号
 * @property string $avatar 头像
 * @property string $merchant_name 商户或公司名
 * @property string $password 密码
 * @property string $salt 盐值
 * @property int $status 状态,0启用,-1禁用
 * @property string $last_login_time 最后一次登录时间
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class Merchants extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchants';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['last_login_time', 'created_time', 'updated_time'], 'safe'],
            [['mobile', 'avatar', 'merchant_name', 'password', 'salt'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'avatar' => 'Avatar',
            'merchant_name' => 'Merchant Name',
            'password' => 'Password',
            'salt' => 'Salt',
            'status' => 'Status',
            'last_login_time' => 'Last Login Time',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

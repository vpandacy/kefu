<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employees".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户ID
 * @property string $email 邮箱帐号
 * @property string $name 姓名
 * @property string $avatar 头像
 * @property string $mobile 手机号
 * @property int $department_id 部门ID
 * @property string $password 密码
 * @property string $salt 盐值
 * @property string $listen_nums 接听数
 * @property int $status 0,启用,1禁用
 * @property int $is_root 是否是超级管理员
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class Employees extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'department_id', 'status', 'is_root'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['email', 'name', 'avatar', 'mobile', 'password', 'salt', 'listen_nums'], 'string', 'max' => 255],
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
            'email' => 'Email',
            'name' => 'Name',
            'avatar' => 'Avatar',
            'mobile' => 'Mobile',
            'department_id' => 'Department ID',
            'password' => 'Password',
            'salt' => 'Salt',
            'listen_nums' => 'Listen Nums',
            'status' => 'Status',
            'is_root' => 'Is Root',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

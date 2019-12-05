<?php

namespace common\models\uc;

use Yii;

/**
 * This is the model class for table "staff".
 *
 * @property int $id 主键
 * @property string $sn 员工sn
 * @property int $merchant_id 商户ID
 * @property string $email 邮箱帐号
 * @property string $name 姓名
 * @property string $avatar 头像
 * @property string $mobile 手机号
 * @property int $department_id 部门ID
 * @property string $password 密码
 * @property string $salt 盐值
 * @property string $listen_nums 接听数
 * @property int $status 0,禁用,1启用
 * @property int $is_root 是否是超级管理员,0不是,1是
 * @property string $app_ids 应用程序ID,以逗号分割
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class Staff extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'department_id', 'status', 'is_root'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['sn', 'email', 'name', 'avatar', 'mobile', 'password', 'salt', 'listen_nums', 'app_ids'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sn' => 'Sn',
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
            'app_ids'   =>  'App Ids',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }

    /**
     * 获取所有的应用程序ID
     * @param int $app_id
     * @return array
     */
    public function checkAppIdOwnerStaff($app_id)
    {
        $app_ids = explode(',', $this->app_ids);

        return in_array($app_ids, $app_ids);
    }
}

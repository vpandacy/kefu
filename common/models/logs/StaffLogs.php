<?php

namespace common\models\logs;

use Yii;

/**
 * This is the model class for table "staff_logs".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户ID
 * @property int $staff_id 员工ID
 * @property string $login_time 登录时间
 * @property string $logout_time 退出时间
 * @property string $created_time 创建时间
 * @property string $updated_time 登录时间
 */
class StaffLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_logs';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('chat_logs_db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'staff_id'], 'integer'],
            [['login_time', 'logout_time', 'created_time', 'updated_time'], 'safe'],
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
            'staff_id' => 'Staff ID',
            'login_time' => 'Login Time',
            'logout_time' => 'Logout Time',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

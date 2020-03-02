<?php

namespace common\models\logs;

use Yii;

/**
 * This is the model class for table "cs_login_logs".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户ID
 * @property int $staff_id 员工ID
 * @property int $ua ua
 * @property int $source 登录终端
 * @property string $login_ip 登录IP
 * @property string $login_time 登录时间
 * @property string $logout_time 退出时间
 * @property string $created_time 创建时间
 * @property string $updated_time 登录时间
 */
class CsLoginLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cs_login_logs';
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
            [['merchant_id', 'staff_id', 'source'], 'integer'],
            [['login_time', 'logout_time', 'created_time', 'updated_time'], 'safe'],
            [['login_ip','ua'], 'string', 'max' => 255],
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
            'ua' => 'Ua',
            'source' => 'Source',
            'login_ip' => 'Login Ip',
            'login_time' => 'Login Time',
            'logout_time' => 'Logout Time',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

<?php

namespace common\models\uc;

use Yii;

/**
 * This is the model class for table "queue_captcha".
 *
 * @property string $id
 * @property int $type 类型 1：邮件 2：手机
 * @property string $account 账号 可能是邮件地址或者手机号码
 * @property string $captcha 验证码
 * @property string $ip 客户端ip
 * @property string $expires_at 过期时间 一般有效期5分钟
 * @property int $status 使用状态 0：未使用 1：已使用
 * @property string $created_time 插入时间
 * @property string $updated_time 最近一次更新时间
 */
class QueueCaptcha extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'queue_captcha';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('chat_uc_db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'integer'],
            [['expires_at', 'created_time', 'updated_time'], 'safe'],
            [['account'], 'string', 'max' => 30],
            [['captcha'], 'string', 'max' => 10],
            [['ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'account' => 'Account',
            'captcha' => 'Captcha',
            'ip' => 'Ip',
            'expires_at' => 'Expires At',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

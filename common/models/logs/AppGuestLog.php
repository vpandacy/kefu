<?php

namespace common\models\logs;

use Yii;

/**
 * This is the model class for table "app_guest_log".
 *
 * @property int $id
 * @property string $cookie cookie
 * @property string $uuid uuid
 * @property string $referer referer
 * @property string $ip ip
 * @property string $ua ua
 * @property string $created_time 插入时间
 */
class AppGuestLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_guest_log';
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
            [['created_time'], 'safe'],
            [['cookie', 'ua'], 'string', 'max' => 400],
            [['uuid'], 'string', 'max' => 100],
            [['referer'], 'string', 'max' => 500],
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
            'cookie' => 'Cookie',
            'uuid' => 'Uuid',
            'referer' => 'Referer',
            'ip' => 'Ip',
            'ua' => 'Ua',
            'created_time' => 'Created Time',
        ];
    }
}

<?php

namespace common\models\logs;

use Yii;

/**
 * This is the model class for table "app_err_logs".
 *
 * @property int $id
 * @property string $app_name app 名字
 * @property string $request_uri 请求uri
 * @property string $referer 来源url
 * @property string $content 日志内容
 * @property string $ip ip
 * @property string $ua ua信息
 * @property string $cookies cookie信息。如果有的话
 * @property string $created_time 插入时间
 */
class AppErrLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_err_logs';
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
            [['app_name'], 'string', 'max' => 30],
            [['request_uri'], 'string', 'max' => 255],
            [['referer'], 'string', 'max' => 500],
            [['content'], 'string', 'max' => 3000],
            [['ip'], 'string', 'max' => 100],
            [['ua', 'cookies'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_name' => 'App Name',
            'request_uri' => 'Request Uri',
            'referer' => 'Referer',
            'content' => 'Content',
            'ip' => 'Ip',
            'ua' => 'Ua',
            'cookies' => 'Cookies',
            'created_time' => 'Created Time',
        ];
    }
}

<?php

namespace common\models\logs;

use Yii;

/**
 * This is the model class for table "app_access_log".
 *
 * @property int $id
 * @property int $merchant_id 商户id
 * @property int $staff_id 用户表id
 * @property string $staff_name 员工名字
 * @property string $referer_url 当前访问的refer
 * @property string $target_url 访问的url
 * @property string $query_params get和post参数
 * @property string $ua 访问ua
 * @property string $ip 访问ip
 * @property string $ip_desc ip对应城市信息
 * @property string $note json格式备注字段
 * @property string $created_time 插入日期
 */
class AppAccessLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_access_log';
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
            [['created_time'], 'safe'],
            [['staff_name'], 'string', 'max' => 20],
            [['referer_url', 'target_url', 'query_params', 'ua', 'note'], 'string', 'max' => 1000],
            [['ip'], 'string', 'max' => 32],
            [['ip_desc'], 'string', 'max' => 30],
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
            'staff_name' => 'Staff Name',
            'referer_url' => 'Referer Url',
            'target_url' => 'Target Url',
            'query_params' => 'Query Params',
            'ua' => 'Ua',
            'ip' => 'Ip',
            'ip_desc' => 'Ip Desc',
            'note' => 'Note',
            'created_time' => 'Created Time',
        ];
    }
}

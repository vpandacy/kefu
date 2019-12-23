<?php

namespace common\models\uc;

use Yii;

/**
 * This is the model class for table "monitor_kf_ws".
 *
 * @property int $id
 * @property int $type 类型 1：register 2：gateway 3：busiworker
 * @property string $name 名称
 * @property string $ip ip
 * @property int $port 端口
 * @property int $start_port gateway 起始端口
 * @property int $count 进程数量
 * @property string $updated_time 最后一次更新时间
 * @property string $created_time 创建时间
 */
class MonitorKfWs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'monitor_kf_ws';
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
            [['type', 'port', 'start_port', 'count'], 'integer'],
            [['updated_time', 'created_time'], 'safe'],
            [['name'], 'string', 'max' => 30],
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
            'name' => 'Name',
            'ip' => 'Ip',
            'port' => 'Port',
            'start_port' => 'Start Port',
            'count' => 'Count',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}

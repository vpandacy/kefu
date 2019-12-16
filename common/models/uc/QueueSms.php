<?php

namespace common\models\uc;

use Yii;

/**
 * This is the model class for table "queue_sms".
 *
 * @property int $id
 * @property string $mobile 手机号码
 * @property string $sign 签名
 * @property string $content 发送手机内容
 * @property string $channel 发送渠道名称
 * @property int $status 发送状态 1成功 0 失败 -2 等待发送 -1 发送中
 * @property string $return_msg 返回信息
 * @property string $taskid 任务id
 * @property string $ip 客户端发送ip
 * @property int $send_number 发送条数，默认1
 * @property string $updated_time 最近一次更新时间
 * @property string $created_time 插入时间
 */
class QueueSms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'queue_sms';
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
            [['status', 'send_number'], 'integer'],
            [['updated_time', 'created_time'], 'safe'],
            [['mobile'], 'string', 'max' => 256],
            [['sign'], 'string', 'max' => 10],
            [['content'], 'string', 'max' => 255],
            [['channel'], 'string', 'max' => 30],
            [['return_msg'], 'string', 'max' => 500],
            [['taskid'], 'string', 'max' => 60],
            [['ip'], 'string', 'max' => 15],
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
            'sign' => 'Sign',
            'content' => 'Content',
            'channel' => 'Channel',
            'status' => 'Status',
            'return_msg' => 'Return Msg',
            'taskid' => 'Taskid',
            'ip' => 'Ip',
            'send_number' => 'Send Number',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}

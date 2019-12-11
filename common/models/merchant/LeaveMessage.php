<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "leave_message".
 *
 * @property int $id 主键
 * @property int $visitor_id 游客ID
 * @property int $merchant_id 商户
 * @property int $group_chat_id 风格ID
 * @property string $mobile 手机号
 * @property string $wechat 微信号
 * @property string $name 姓名
 * @property string $message 留言信息
 * @property int $status 是否处理,0未处理,1已处理
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class LeaveMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'leave_message';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('chat_db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visitor_id', 'merchant_id', 'group_chat_id', 'status'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['mobile', 'wechat', 'name', 'message'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visitor_id' => 'Visitor ID',
            'merchant_id' => 'Merchant ID',
            'group_chat_id' => 'Group Chat ID',
            'mobile' => 'Mobile',
            'wechat' => 'Wechat',
            'name' => 'Name',
            'message' => 'Message',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

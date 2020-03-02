<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "guest_chat_log".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户id
 * @property int $member_id 会员ID
 * @property int $cs_id 客服ID
 * @property string $cs_name 客服昵称
 * @property int $guest_log_id 日志表ID,标识哪次聊天.
 * @property string $uuid 游客ID
 * @property string $from_id 发送方ID,用来回显聊天记录
 * @property string $content 消息内容
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class GuestChatLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guest_chat_log';
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
            [['merchant_id', 'member_id', 'cs_id', 'guest_log_id'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['cs_name', 'uuid', 'from_id', 'content'], 'string', 'max' => 255],
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
            'member_id' => 'Member ID',
            'cs_id' => 'Cs ID',
            'cs_name' => 'Cs Name',
            'guest_log_id' => 'Guest Log ID',
            'uuid' => 'Uuid',
            'from_id' => 'From ID',
            'content' => 'Content',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

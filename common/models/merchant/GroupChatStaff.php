<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "group_chat_staff".
 *
 * @property int $id ID
 * @property int $merchant_id 商户ID
 * @property int $group_chat_id 风格ID
 * @property int $staff_id 员工ID
 * @property int $status 0异常,1正常
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class GroupChatStaff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group_chat_staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'group_chat_id', 'staff_id', 'status'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
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
            'group_chat_id' => 'Group Chat ID',
            'staff_id' => 'Staff ID',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

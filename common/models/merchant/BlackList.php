<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "black_list".
 *
 * @property int $id 主键
 * @property string $ip ip地址
 * @property int $visitor_id 访客ID
 * @property int $merchant_id 商户ID
 * @property int $staff_id 接待员工ID
 * @property int $status 已删除,1正常
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class BlackList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'black_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['visitor_id', 'merchant_id', 'staff_id', 'status'], 'integer'],
            [['created_time', 'updated_time', 'expired_time'], 'safe'],
            [['ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'visitor_id' => 'Visitor ID',
            'merchant_id' => 'Merchant ID',
            'staff_id' => 'Staff ID',
            'status' => 'Status',
            'expired_time' => 'Expired Time',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

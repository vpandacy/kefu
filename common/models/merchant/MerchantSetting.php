<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "merchant_setting".
 *
 * @property int $id 商户配置表
 * @property int $merchant_id 商户ID
 * @property int $auto_disconnect 自动断开时长
 * @property string $greetings 问候语
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class MerchantSetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'auto_disconnect'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['greetings'], 'string', 'max' => 255],
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
            'auto_disconnect' => 'Auto Disconnect',
            'greetings' => 'Greetings',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

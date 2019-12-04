<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "common_word".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户ID
 * @property string $words 常用语
 * @property int $status 0已删除,1正常
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class CommonWord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'common_word';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['words'], 'string', 'max' => 255],
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
            'words' => 'Words',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "common_word".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户ID
 * @property string $title 常用语标题
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
            [['merchant_id', 'status'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['title'], 'string', 'max' => 50],
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
            'title' => 'Title',
            'words' => 'Words',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "group_chat".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户ID
 * @property string $desc 描述
 * @property string $title 标题
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class GroupChat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group_chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','merchant_id','status'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['sn','desc', 'title'], 'string', 'max' => 255],
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
            'sn' => 'Sn',
            'desc' => 'Desc',
            'title' => 'Title',
            'status'=> 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

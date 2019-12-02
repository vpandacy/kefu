<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $id 主键
 * @property string $name 部门名
 * @property int $merchant_id 所属商户
 * @property int $status 状态,0已删除,1正常
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'merchant_id', 'status'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'merchant_id' => 'Merchant ID',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id 主键
 * @property string $name 角色名
 * @property int $merchant_id 商户ID
 * @property int $status 状态,0禁用,1启用
 * @property string $created_time 创建时间
 * @property string $udated_time 更新时间
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'status'], 'integer'],
            [['created_time', 'udated_time'], 'safe'],
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
            'udated_time' => 'Udated Time',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "departments".
 *
 * @property int $id 主键
 * @property string $name 部门名
 * @property int $merchant_id 所属商户
 * @property int $is_deleted 是否删除,0未删除,1删除
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class Departments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'merchant_id', 'is_deleted'], 'integer'],
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
            'is_deleted' => 'Is Deleted',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "staff_role".
 *
 * @property int $id 主键
 * @property int $staff_id 员工ID
 * @property int $role_id 角色ID
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class StaffRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_id', 'role_id'], 'integer'],
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
            'staff_id' => 'Staff ID',
            'role_id' => 'Role ID',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

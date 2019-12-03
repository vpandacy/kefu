<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "role_action".
 *
 * @property int $id 主键
 * @property int $role_id 角色ID
 * @property int $action_id 权限ID
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class RoleAction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_action';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'action_id','status'], 'integer'],
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
            'role_id' => 'Role ID',
            'action_id' => 'Action ID',
            'status'    =>  'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

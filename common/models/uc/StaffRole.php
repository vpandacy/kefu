<?php

namespace common\models\uc;

use Yii;

/**
 * This is the model class for table "staff_role".
 *
 * @property int $id 主键
 * @property int $staff_id 员工ID
 * @property int $role_id 角色ID
 * @property int $app_id 应用ID,详细请查看uc下的常量
 * @property int $status 状态,0异常,1正常
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class StaffRole extends \common\models\uc\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_role';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('chat_uc_db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_id', 'role_id', 'app_id', 'status'], 'integer'],
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
            'app_id' => 'App ID',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

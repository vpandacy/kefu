<?php

namespace common\models\uc;

use Yii;

/**
 * This is the model class for table "role_action".
 *
 * @property int $id 主键
 * @property int $role_id 角色ID
 * @property int $action_id 权限ID
 * @property int $app_id 应用ID,详细请查看uc下的常量
 * @property int $status 0,异常,1正常
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class RoleAction extends \common\models\uc\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_action';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_uc');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'action_id', 'app_id', 'status'], 'integer'],
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
            'app_id' => 'App ID',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

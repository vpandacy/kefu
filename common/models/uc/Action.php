<?php

namespace common\models\uc;

use Yii;

/**
 * This is the model class for table "action".
 *
 * @property int $id 主键
 * @property string $level1_name 一级菜单名称
 * @property string $level2_name 二级菜单名称
 * @property string $name 操作名称
 * @property string $urls 操作链接
 * @property int $weight 权重
 * @property int $level1_weight 一级菜单排序权重
 * @property int $level2_weight 一级菜单排序权重
 * @property int $app_id 应用ID
 * @property int $status 状态 1:启用 0:禁用
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class Action extends \common\models\uc\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'action';
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
            [['weight', 'level1_weight', 'level2_weight', 'app_id', 'status'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['level1_name', 'level2_name'], 'string', 'max' => 50],
            [['name', 'urls'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level1_name' => 'Level1 Name',
            'level2_name' => 'Level2 Name',
            'name' => 'Name',
            'urls' => 'Urls',
            'weight' => 'Weight',
            'level1_weight' => 'Level1 Weight',
            'level2_weight' => 'Level2 Weight',
            'app_id' => 'App ID',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

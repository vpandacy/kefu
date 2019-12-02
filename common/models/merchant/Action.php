<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "action".
 *
 * @property int $id 主键
 * @property string $level1_name 一级菜单名称
 * @property string $level2_name 二级菜单名称
 * @property string $name 操作名称
 * @property string $urls 操作链接
 * @property int $level1_weight 一级菜单排序权重
 * @property int $level2_weight 一级菜单排序权重
 * @property int $status 状态 1:启用 0:禁用
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class Action extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'action';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level1_weight', 'level2_weight', 'status'], 'integer'],
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
            'level1_weight' => 'Level1 Weight',
            'level2_weight' => 'Level2 Weight',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

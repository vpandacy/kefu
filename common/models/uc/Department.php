<?php

namespace common\models\uc;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $id 主键
 * @property string $name 部门名
 * @property int $merchant_id 所属商户
 * @property int $app_id 应用ID,详细请查看uc下的常量
 * @property int $status 状态,0已删除,1正常
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class Department extends \common\models\uc\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
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
            [['merchant_id', 'app_id', 'status'], 'integer'],
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
            'app_id' => 'App ID',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

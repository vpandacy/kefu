<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "reception_rule".
 *
 * @property int $id 主键
 * @property int $group_chat_id 风格分组ID
 * @property int $merchant_id 商户ID
 * @property int $distribution_mode 分配方式
 * @property int $reception_rule 接待规则,0人工客服优先
 * @property int $reception_strategy 接待策略,0风格分组优先,1管理员优先
 * @property int $shunt_mode 分流规则,0指定客服,1区域分流,2搜索引擎
 * @property string $shunt_data 分流接待规则数据
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class ReceptionRule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reception_rule';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('chat_db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_chat_id', 'merchant_id', 'distribution_mode', 'reception_rule', 'reception_strategy', 'shunt_mode'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['shunt_data'], 'string', 'max' => 5000],
            [['merchant_id', 'group_chat_id'], 'unique', 'targetAttribute' => ['merchant_id', 'group_chat_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_chat_id' => 'Group Chat ID',
            'merchant_id' => 'Merchant ID',
            'distribution_mode' => 'Distribution Mode',
            'reception_rule' => 'Reception Rule',
            'reception_strategy' => 'Reception Strategy',
            'shunt_mode' => 'Shunt Mode',
            'shunt_data' => 'Shunt Data',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "black_list".
 *
 * @property int $id 主键
 * @property string $ip ip地址
 * @property string $uuid 游客ID
 * @property int $merchant_id 商户ID
 * @property int $cs_id 客服ID
 * @property int $status 已删除,1正常
 * @property string $expired_time 失效时间
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class BlackList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'black_list';
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
            [['merchant_id', 'cs_id', 'status'], 'integer'],
            [['expired_time', 'created_time', 'updated_time'], 'safe'],
            [['ip', 'uuid'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'uuid' => 'Uuid',
            'merchant_id' => 'Merchant ID',
            'cs_id' => 'Cs ID',
            'status' => 'Status',
            'expired_time' => 'Expired Time',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

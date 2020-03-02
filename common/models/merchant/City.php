<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string $name
 * @property int $province_id 省id
 * @property string $province 省名
 * @property string $province_alias_name 省份别名
 * @property int $city_id 城市id
 * @property string $city 城市名称
 * @property int $area_id 区域id
 * @property string $area 区域名称
 * @property int $region_id 区域id，0：其他 1：华北 2：东北 3：西北 4：华南 5：华中 6：西南 7：华东
 * @property string $region_name 区域名称 如：华北
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
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
            [['id', 'province_id', 'city_id', 'area_id'], 'required'],
            [['id', 'province_id', 'city_id', 'area_id', 'region_id'], 'integer'],
            [['name', 'province', 'province_alias_name', 'city', 'area', 'region_name'], 'string', 'max' => 20],
            [['id'], 'unique'],
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
            'province_id' => 'Province ID',
            'province' => 'Province',
            'province_alias_name' => 'Province Alias Name',
            'city_id' => 'City ID',
            'city' => 'City',
            'area_id' => 'Area ID',
            'area' => 'Area',
            'region_id' => 'Region ID',
            'region_name' => 'Region Name',
        ];
    }
}

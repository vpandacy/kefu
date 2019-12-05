<?php

namespace common\models\uc;

use Yii;

/**
 * This is the model class for table "merchant".
 *
 * @property int $id 默认主键
 * @property string $name 商户或公司名
 * @property string $sn 商户编号
 * @property string $logo 商户图标
 * @property string $desc 商户简介
 * @property string $contact 商户联系方式
 * @property int $status 状态,-2,待审核,-1,审核失败,0,禁用,1,审核成功
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class Merchant extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['name', 'sn', 'logo', 'desc', 'contact'], 'string', 'max' => 255],
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
            'sn' => 'Sn',
            'logo' => 'Logo',
            'desc' => 'Desc',
            'contact' => 'Contact',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

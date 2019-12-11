<?php

namespace common\models\merchant;

use Yii;

/**
 * This is the model class for table "group_chat_setting".
 *
 * @property int $id 主键
 * @property int $group_chat_id 风格ID
 * @property int $merchant_id 商户ID
 * @property string $company_name 公司名称
 * @property string $company_desc 公司简介
 * @property string $company_logo 公司logo
 * @property int $is_history 是否展示消息记录,0展示,1不展示
 * @property int $province_id 所在省份,该省外不能使用客服
 * @property int $is_active 是否主动发起会话,0主动,1不主动
 * @property int $windows_status 浮动窗口展示状态,0最小化,1展示
 * @property int $is_force 新消息是否强制弹窗,0强制,1不强制
 * @property int $lazy_time 首次发起时间,单位s
 * @property int $is_show_num 是否展示消息数量,0展示,1不展示
 * @property string $created_time 创建时间
 * @property string $updated_time 更新时间
 */
class GroupChatSetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group_chat_setting';
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
            [['group_chat_id', 'merchant_id', 'is_history', 'province_id', 'is_active', 'windows_status', 'is_force', 'lazy_time', 'is_show_num'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['company_name', 'company_desc', 'company_logo'], 'string', 'max' => 255],
            [['group_chat_id'], 'unique'],
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
            'company_name' => 'Company Name',
            'company_desc' => 'Company Desc',
            'company_logo' => 'Company Logo',
            'is_history' => 'Is History',
            'province_id' => 'Province ID',
            'is_active' => 'Is Active',
            'windows_status' => 'Windows Status',
            'is_force' => 'Is Force',
            'lazy_time' => 'Lazy Time',
            'is_show_num' => 'Is Show Num',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }
}

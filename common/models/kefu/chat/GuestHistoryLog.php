<?php

namespace common\models\kefu\chat;

use Yii;

/**
 * This is the model class for table "guest_history_log".
 *
 * @property int $id
 * @property int $merchant_id 商户id
 * @property int $member_id 会员id
 * @property string $uuid 用户uuid
 * @property string $client_id ws 客户端id
 * @property int $cs_id 接待客服id
 * @property string $referer_url 来源url
 * @property int $referer_media 来源媒体
 * @property string $land_url 落地页url
 * @property string $client_ip 游客ip
 * @property string $client_ua 游客浏览器信息
 * @property int $province_id 游客来源省id
 * @property int $city_id 游客来源城市id
 * @property int $chat_stype_id 对话分格id
 * @property string $closed_time 关闭对话时间
 * @property int $chat_duration 聊天时长，单位秒
 * @property int $status 聊天状态 ：-1 聊天中 1：聊天正常结束 0：聊天异常结束
 * @property int $source 来源 1：PC 2：手机H5 3：微信
 * @property string $updated_time 最后一次更新时间
 * @property string $created_time 创建时间
 */
class GuestHistoryLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guest_history_log';
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
            [['merchant_id', 'member_id', 'cs_id', 'referer_media', 'province_id', 'city_id', 'chat_stype_id', 'chat_duration', 'status', 'source'], 'integer'],
            [['closed_time'], 'required'],
            [['closed_time', 'updated_time', 'created_time'], 'safe'],
            [['uuid', 'client_id'], 'string', 'max' => 64],
            [['referer_url', 'land_url'], 'string', 'max' => 1000],
            [['client_ip'], 'string', 'max' => 20],
            [['client_ua'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => 'Merchant ID',
            'member_id' => 'Member ID',
            'uuid' => 'Uuid',
            'client_id' => 'Client ID',
            'cs_id' => 'Cs ID',
            'referer_url' => 'Referer Url',
            'referer_media' => 'Referer Media',
            'land_url' => 'Land Url',
            'client_ip' => 'Client Ip',
            'client_ua' => 'Client Ua',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'chat_stype_id' => 'Chat Stype ID',
            'closed_time' => 'Closed Time',
            'chat_duration' => 'Chat Duration',
            'status' => 'Status',
            'source' => 'Source',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}

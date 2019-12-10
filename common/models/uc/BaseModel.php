<?php
namespace common\models\uc;

use yii\db\ActiveRecord;
use Yii;

class BaseModel extends ActiveRecord
{
    /**
     * uc db库.
     * @return null|object|\yii\db\Connection
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('chat_uc_db');
    }
}
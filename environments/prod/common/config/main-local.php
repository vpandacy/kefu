<?php
return [
    'components' => [
        'chat_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=pc-wz9z908280ydsvi58.rwlb.rds.aliyuncs.com;dbname=chat_db',
            'username' => 'chat_www',
            'password' => 'dGGIr1wU!86tVAZX',
            'charset' => 'utf8mb4',
            'commandClass'  =>  'common\components\Command',
        ],
        'chat_uc_db' =>  [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=pc-wz9z908280ydsvi58.rwlb.rds.aliyuncs.com;dbname=chat_uc_db',
            'username' => 'chat_www',
            'password' => 'dGGIr1wU!86tVAZX',
            'charset' => 'utf8mb4',
            'commandClass'  =>  'common\components\Command',
        ],
        'chat_logs_db' =>  [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=pc-wz9z908280ydsvi58.rwlb.rds.aliyuncs.com;dbname=chat_logs_db',
            'username' => 'chat_www',
            'password' => 'dGGIr1wU!86tVAZX',
            'charset' => 'utf8mb4',
            'commandClass'  =>  'common\components\Command',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'list_guest' => [//当做队列
            'class' => 'common\components\redis\RedisConnection',
            'host'     => 'r-wz9upztf8livi4vcn2.redis.rds.aliyuncs.com',
            'port'     => 6379,
            'database' => 0,
            'password' => 'xqYlAcF9CvqC1U4i',
            'prefix' => 'kf_guest_'
        ],
        'list_cs' => [//当做队列
            'class' => 'common\components\redis\RedisConnection',
            'host'     => 'r-wz9upztf8livi4vcn2.redis.rds.aliyuncs.com',
            'port'     => 6379,
            'database' => 0,
            'password' => 'xqYlAcF9CvqC1U4i',
            'prefix' => 'kf_cs_'
        ],

        'list_chat_log' => [
            'class' => 'common\components\redis\RedisConnection',
            'host'     => 'r-wz9upztf8livi4vcn2.redis.rds.aliyuncs.com',
            'port'     => 6379,
            'database' => 0,
            'password' => 'xqYlAcF9CvqC1U4i',
            'prefix' => 'kf_chat_'
        ],
        'cache' => [//当做缓存
            'class' => 'common\components\redis\RedisCache',
            'redis' => [
                'database' => 0,
                'host'     => 'r-wz9upztf8livi4vcn2.redis.rds.aliyuncs.com',
                'port'     => 6379,
                'password' => 'xqYlAcF9CvqC1U4i',
                'prefix' => 'kf_cache',

            ]
        ],
    ],
];

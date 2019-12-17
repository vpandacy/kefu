<?php
return [
    'components' => [
        'chat_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=chat_db',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        'chat_uc_db' =>  [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=chat_uc_db',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        'chat_logs_db' =>  [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=chat_logs_db',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'list_001' => [//当做队列
            'class' => 'common\components\redis\RedisConnection',
            'host' => '47.112.117.135',
            'port' => 6379,
            'database' => 0
        ],
        'list_chat_log' => [
            'class' => 'common\components\redis\RedisConnection',
            'host' => '47.112.117.135',
            'port' => 6379,
            'database' => 0,
            'prefix' => 'kf_chat_'
        ],
        'cache' => [//当做缓存
            'class' => 'common\components\redis\RedisCache',
            'redis' => [
                'database' => 0,
                'host' => '47.112.117.135',
                'port' => 6379,
                'prefix' => 'CACHE_HSH_'
            ]
        ],
    ],
];

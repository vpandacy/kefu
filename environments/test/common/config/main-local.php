<?php
return [
    'components' => [
        'chat_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=chat_db',
            'username' => 'www',
            'password' => 'mqQ7sJ7CSLZztTaY',
            'charset'  => 'utf8mb4',
            'commandClass'  =>  'common\components\Command',
        ],
        'chat_uc_db' =>  [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=chat_uc_db',
            'username' => 'www',
            'password' => 'mqQ7sJ7CSLZztTaY',
            'charset'  => 'utf8mb4',
            'commandClass'  =>  'common\components\Command',
        ],
        'chat_logs_db' =>  [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=chat_logs_db',
            'username' => 'www',
            'password' => 'mqQ7sJ7CSLZztTaY',
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
        'list_001' => [//当做队列
            'class' => 'common\components\redis\RedisConnection',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0
        ],
        'list_guest' => [//当做队列
            'class' => 'common\components\redis\RedisConnection',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
            'prefix' => 'kf_guest_'
        ],
        'list_cs' => [//当做队列
            'class' => 'common\components\redis\RedisConnection',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
            'prefix' => 'kf_cs_'
        ],
        'list_chat_log' => [
            'class' => 'common\components\redis\RedisConnection',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
            'prefix' => 'kf_chat_'
        ],
        'cache' => [//当做缓存
            'class' => 'common\components\redis\RedisCache',
            'redis' => [
                'database' => 0,
                'host' => '127.0.0.1',
                'port' => 6379,
                'prefix' => 'kf_'
            ]
        ],
    ],
];

<?php
return [
    'components' => [
        'chat_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=chat_db',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'chat_uc_db' =>  [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=chat_uc_db',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];

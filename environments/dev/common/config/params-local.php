<?php
return [
    'domains' => [
        'www' => 'http://www.kefu.dev.hsh568.cn/',
        'merchant' => 'http://www.kefu.dev.hsh568.cn/merchant',
        'cs' => 'http://www.kefu.dev.hsh568.cn/cs',
        'static' => 'http://static.dev.hsh568.cn',
        'uc' => 'http://uc.kefu.dev.hsh568.cn',
    ],
    'websocket' => 'ws://www.kefu.dev.hsh568.cn/ws/',
    'cdn' => [
        'hsh' => [
            'https' => 'http://cdn.pic.test.hsh568.cn',
            'http' => 'http://cdn.pic.test.hsh568.cn'
        ],
        'qiniu_config' => [
            'ak' => 'mZ-oBLEtEh4M2o2TWnFbVXn5P1KxuSPwqXAtZW_z',
            'sk' => 'dsuPhLCGpBUfzsynpdGKaoKOUHdN2y5jJx4AXBjb',
            'bucket' => [
                'hsh' => 'stkf'
            ]
        ]
    ],
    'cookies' => [
        'staff' => [
            'name' => 'staff_cookie',
            'domain' => '.kefu.dev.hsh568.cn',
        ],
        "guest" => [
            "name" => "guest",
            'domain' => '.kefu.dev.hsh568.cn',
        ]
    ],
    'guest' => [
        "register" => [
            "host" => '0.0.0.0:1238',
            'name' => 'guest_register'
        ],
        'gateway' => [
            "host" => '0.0.0.0:8282',
            "name" => 'guest_gateway',
            "register_host" => "0.0.0.0:1238"
        ],
        'busi_worker' => [
            "name" => 'guest_busworker',
            "register_host" => "0.0.0.0:1238"
        ]
    ]
];

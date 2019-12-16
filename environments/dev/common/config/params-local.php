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
        ],
    ],
    'guest' => [
        "register" => [
            "host" => '0.0.0.0:8210',
            'name' => 'guest_register'
        ],
        'gateway' => [
            "host" => '0.0.0.0:8230',
            'ip' => 'www.kefu.test.hsh568.cn',
            'port' => '8230',
            'start_port' => 8231 ,
            "name" => 'guest_gateway',
            "register_host" => "0.0.0.0:8210",
        ],
        'busi_worker' => [
            "name" => 'guest_busworker',
            "register_host" => "0.0.0.0:8210",
            "inner" => [
                "host" => "0.0.0.0:8220",
                "name" => "guest_transfer"
            ]
        ],
        'push' => [
            'host' => '127.0.0.1:8220'
        ]
    ],
    'cs' => [
        "register" => [
            "host" => '0.0.0.0:9210',
            'name' => 'cs_register'
        ],
        'gateway' => [
            "host" => '0.0.0.0:9230',
            'ip' => 'www.kefu.test.hsh568.cn',
            'port' => '9230',
            'start_port' => 9231 ,
            "name" => 'cs_gateway',
            "register_host" => "0.0.0.0:9210",
        ],
        'busi_worker' => [
            "name" => 'cs_busworker',
            "register_host" => "0.0.0.0:9210",
            "inner" => [
                "host" => "0.0.0.0:9220",
                "name" => "cs_transfer"
            ]
        ],
        'push' => [
            'host' => '127.0.0.1:9220'
        ]
    ]
];

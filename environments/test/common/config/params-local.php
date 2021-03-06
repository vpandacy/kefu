<?php
return [
    'domains' => [
        'www' => 'http://www.kefu.test.hsh568.cn',
        'merchant' => 'http://www.kefu.test.hsh568.cn/merchant',
        'cs' => 'http://www.kefu.test.hsh568.cn/cs',
        'static' => 'http://static.kefu.test.hsh568.cn',
        'uc' => 'http://uc.kefu.test.hsh568.cn',
        'admin'     => 'http://admin.kefu.test.hsh568.cn',
    ],
    'websocket' => 'ws://www.kefu.test.hsh568.cn/ws/',
    'cdn' => [
        'hsh' => [
            'https' => 'http://cdn.pic.kefu.test.hsh568.cn',
            'http' => 'http://cdn.pic.kefu.test.hsh568.cn'
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
            'domain' => '.kefu.test.hsh568.cn',
        ],
        "guest" => [
            "name" => "guest",
            'domain' => '.kefu.test.hsh568.cn',
        ],
        'validate_code' =>  [
            'name'      =>  'validate_code',
            'domain'    =>  '.kefu.test.hsh568.cn'
        ],
    ],
    'guest_1' => [
        "register" => [
            'ip' => 'www.kefu.test.hsh568.cn',
            'port' => '8210',
            'name' => 'guest_register'
        ],
        'gateway_1' => [
            'ip' => 'www.kefu.test.hsh568.cn',
            'port' => '8230',
            'start_port' => 8231 ,
            "name" => 'guest_gateway',
            "register_host" => "0.0.0.0:8210",
        ],
        'gateway_2' => [
            'ip' => 'www.kefu.test.hsh568.cn',
            'port' => '8240',
            'start_port' => 8241 ,
            "name" => 'guest_gateway',
            "register_host" => "0.0.0.0:8210",
        ],
        'busi_worker_1' => [
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
    'cs_1' => [
        "register" => [
            'ip' => 'www.kefu.test.hsh568.cn',
            'port' => '9210',
            'name' => 'cs_register'
        ],
        'gateway_1' => [
            'ip' => 'www.kefu.test.hsh568.cn',
            'port' => '9230',
            'start_port' => 9231 ,
            "name" => 'cs_gateway',
            "register_host" => "0.0.0.0:9210",
        ],
        'gateway_2' => [
            'ip' => 'www.kefu.test.hsh568.cn',
            'port' => '9240',
            'start_port' => 9241 ,
            "name" => 'cs_gateway',
            "register_host" => "0.0.0.0:9210",
        ],
        'busi_worker_1' => [
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
    ],
    "sysconfig" => [
        "admin"=>[
            "footer" => "Admin系统",
            "menu_title" => "Admin系统"
        ]
    ],
    // 聊天默认配置.
    'default_chat_config'   =>  [
        'auto_disconnect' =>  30,
        // 接听数.
        'listen_num'    =>  [
            'min'   =>  1,
            'max'   =>  50
        ]
    ],
];

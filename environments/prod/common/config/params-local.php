<?php
return [
    'domains' => [
        'www' => 'http://www.kf.hsh568.cn',
        'merchant' => 'http://www.kf.hsh568.cn/merchant',
        'cs' => 'http://www.kf.hsh568.cn/cs',
        'static' => 'http://cdn1.static.corp.hsh568.cn',
        'uc' => 'http://uc.kf.hsh568.cn',
        'admin' => 'http://admin.kf.hsh568.cn',
    ],
    'websocket' => 'ws://www.kf.hsh568.cn/ws/',
    'cdn' => [
        'hsh' => [
            'https' => 'https://chat-resource.cdn.corp.hsh568.cn',
            'http' => 'http://chat-resource.cdn.corp.hsh568.cn'
        ],
        'qiniu_config' => [
            'ak' => '7Z8CeNzQRSr7m-sOr0hFVhgaOv_qkG03icbRZ7U9',
            'sk' => '_mO052qgj03HKWfojiDnPSN4zLukuElD37Q7x_MW',
            'bucket' => [
                'hsh' => 'chat-resource'
            ]
        ]
    ],
    'cookies' => [
        'staff' => [
            'name' => 'staff_cookie',
            'domain' => '.kf.hsh568.cn',
        ],
        "guest" => [
            "name" => "guest",
            'domain' => '.kf.hsh568.cn',
        ],
        'validate_code' =>  [
            'name'      =>  'validate_code',
            'domain'    =>  '.kf.hsh568.cn'
        ],
    ],
    'guest_1' => [
        "register" => [
            'ip' => '172.18.23.99',
            'port' => '8210',
            'name' => 'guest_register'
        ],
        'gateway_1' => [
            'ip' => 'www.kf.hsh568.cn',
            'port' => '8230',
            'start_port' => 8231 ,
            "name" => 'guest_gateway',
            "register_host" => "172.18.23.99:8210",
        ],
        'gateway_2' => [
            'ip' => 'www.kf.hsh568.cn',
            'port' => '8240',
            'start_port' => 8241 ,
            "name" => 'guest_gateway',
            "register_host" => "172.18.23.99:8210",
        ],
        'busi_worker_1' => [
            "name" => 'guest_busworker',
            "register_host" => "172.18.23.99:8210",
            "inner" => [
                "host" => "0.0.0.0:8220",
                "name" => "guest_transfer"
            ]
        ],
        'push' => [
            'host' => '172.18.23.99:8220'
        ]
    ],
    'cs_1' => [
        "register" => [
            'ip' => '172.18.23.99',
            'port' => '9210',
            'name' => 'cs_register'
        ],
        'gateway_1' => [
            'ip' => 'www.kf.hsh568.cn',
            'port' => '9230',
            'start_port' => 9231 ,
            "name" => 'cs_gateway',
            "register_host" => "172.18.23.99:9210",
        ],
        'gateway_2' => [
            'ip' => 'www.kf.hsh568.cn',
            'port' => '9240',
            'start_port' => 9241 ,
            "name" => 'cs_gateway',
            "register_host" => "172.18.23.99:9210",
        ],
        'busi_worker_1' => [
            "name" => 'cs_busworker',
            "register_host" => "172.18.23.99:9210",
            "inner" => [
                "host" => "0.0.0.0:9220",
                "name" => "cs_transfer"
            ]
        ],
        'push' => [
            'host' => '172.18.23.99:9220'
        ],
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
    "sysconfig" => [
        "admin"=>[
            "footer" => "Admin系统",
            "menu_title" => "Admin系统"
        ]
    ]
];

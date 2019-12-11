<?php
return [
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

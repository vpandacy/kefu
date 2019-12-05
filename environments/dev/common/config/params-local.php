<?php
return [
    'domains' => [
        'www'       => 'http://www.kefu.dev.hsh568.cn/',
        'admin'     => 'http://admin.kefu.dev.hsh568.cn',
        'merchant'  => 'http://www.kefu.dev.hsh568.cn/merchant',
        'cs'        => 'http://www.kefu.dev.hsh568.cn/cs',
        'static'    => 'http://static.dev.hsh568.cn',
        'uc'        =>  'http://uc.kefu.dev.hsh568.cn',
    ],
    'websocket' => 'ws://www.kefu.dev.hsh568.cn/ws/',
    'cdn' => [
        'hsh' => [
            'https' => 'http://cdn.static.test.jiatest.cn',
            'http' => 'http://cdn.static.test.jiatest.cn'
        ],
        'qiniu_config' => [
            'ak' => 'mZ-oBLEtEh4M2o2TWnFbVXn5P1KxuSPwqXAtZW_z',
            'sk' => 'dsuPhLCGpBUfzsynpdGKaoKOUHdN2y5jJx4AXBjb',
            'bucket' => [
                'hsh' => 'hsh'
            ]
        ]
    ],
];

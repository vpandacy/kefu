<?php
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'console/runtime',
            'www/runtime',
            'www/web/assets',
            'uc/runtime',
            'uc/web/assets',
            'admin/runtime',
            'admin/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [

        ],
    ],
    'test' => [
        'path' => 'test',
        'setWritable' => [
            'console/runtime',
            'www/runtime',
            'www/web/assets',
            'uc/runtime',
            'uc/web/assets',
            'admin/runtime',
            'admin/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [

        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'console/runtime',
            'www/runtime',
            'www/web/assets',
            'uc/runtime',
            'uc/web/assets',
            'admin/runtime',
            'admin/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
        ],
    ],
];

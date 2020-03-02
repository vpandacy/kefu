<?php
return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => false,
    'rules' => [
        '/<controller:\w\w+>/<action:\w\w+>' => '<controller>/<action>',
        '/' => '/default/index',
        '/<module:(cs|merchant)>/<controller:\w+>/<action:\w+>/<id:\d+>' =>'<module>/<controller>/<action>',
        '/<module:(cs|merchant)>/<controller:\w+>/<action:\w+>' =>'<module>/<controller>/<action>',
        // 指定路由.
        '/<msn:\w+>/c'  =>  'code/index',
        '/<msn:\w+>/c/<code:\w+>'  =>  'code/index',
        '/<msn:\w+>/<controller:(code|visitor)>/<action:\w+>'  =>  '<controller>/<action>',
        '/<msn:\w+>/<controller:(code|visitor)>/'  =>  '<controller>/<action>'
    ],
];
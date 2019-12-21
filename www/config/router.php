<?php
return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => false,
    'rules' => [
        '/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '/' => '/default/index',
        '/<module:(cs|merchant)>/<controller:\w+>/<action:\w+>/<id:\d+>' =>'<module>/<controller>/<action>',
        '/<module:(cs|merchant)>/<controller:\w+>/<action:\w+>' =>'<module>/<controller>/<action>',
        '/<msn:\w+>/<controller:(code|visitor)>/<action:\w+>'  =>  '<controller>/<action>',
        '/<msn:\w+>/<controller:(code|visitor)>/'  =>  '<controller>/<action>'
    ],
];
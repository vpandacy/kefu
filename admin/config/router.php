<?php
return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => false,
    'rules' => [
        '/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '/' => 'default/index'
    ],
];

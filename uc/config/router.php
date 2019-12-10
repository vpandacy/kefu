<?php
return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => false,
    'rules' => [
        // 个性化设置要放到前面.不然会影响匹配过程.
        '/<app_name:(www)>/<controller:[\w\-_]+>/<action:[\w\-_]+>'=> '<controller>/<action>',
        '/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        '/'   => '/default/application',
    ],
];
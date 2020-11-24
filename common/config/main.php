<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'defaultRoute' => 'landing/index',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=1021437-task-force-1',
            'username' => 'taskforce',
            'password' => 'kMfOdXaaOKUWVCvc',
            'charset' => 'utf8',
        ],
//        'urlManager' => [
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//            'enableStrictParsing' => false,
//            'rules' => [
//                'tasks' => 'tasks/index',
//                'users' => 'users/index',
//                'landing' => 'landing/index',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//            ],
//        ],
    ],
];

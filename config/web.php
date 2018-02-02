<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

// our libs
require('./../components/auth_lib.php');

//echo dirname(__DIR__) . '---' . __DIR__; die;

$config = [
    'id' => 'basic',
    'name' => 'Events^',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'site/index',
    'layout' => false,
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'components' => [
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'T679bjJqu5P6WjP1KIm-V3jTMZ3z8QXa',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User2',
            'enableAutoLogin' => true,
            'loginUrl' => '/site/login',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' =>     $params['sw_host'],
                'username' => $params['sw_frommail'],
                'password' => $params['sw_pass'],
                'port' =>     $params['sw_port'],
                'encryption' => $params['sw_enc'],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        //*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'suffix' => '.jade',
            'rules' => [
            ],
        ],
        //*/
    ],
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module'],
        'admiration' => [
            'class' => 'app\modules\admiration\module',
            'layout' => 'main'
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

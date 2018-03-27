<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-hotel',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'hotel\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'hotel\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null,
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
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'default/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'api/users','backend/users'
                    ],
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST register' => 'register',
                    ],
                ],
            ]
        ],
    ],
    'defaultRoute' => 'default',
    'modules' => [
        'api' => [
            'class' => 'hotel\modules\api\Module',
        ],
        'backend' => [
            'class' => 'hotel\modules\backend\Module',
        ],
        'admin' => [
            'class' => 'hotel\modules\admin\Module',
        ],
    ],
    'params' => $params,
];

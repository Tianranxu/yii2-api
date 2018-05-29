<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'v1' => [
            'class' => 'frontend\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        /*'errorHandler' => [
            'errorAction' => 'site/error',
        ],*/
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                'POST v1/login' => 'v1/login/login',
                'POST v1/userdata' => 'v1/login/pushuser',
                //'GET <module:(v)\d+>/<controller:\w+>/search' => '<module>/<controller>/search',
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'v1/doubts',
                    'extraPatterns' => [
                        'POST list' => 'list',
                        'POST one' => 'one',
                        'POST like' => 'like',
                        'POST unlike' => 'unlike',
                        'POST encourage' => 'encourage',
                        'POST discourage' => 'discourage',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'v1/questions',
                    'extraPatterns' => [
                        'POST list' => 'list',
                        'POST answer' => 'answer'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'v1/colleges',
                    'extraPatterns' => [
                        'POST regions' => 'regions',
                        'POST list' => 'list'
                    ]
                ],
                'POST v1/strategy/list' => 'v1/strategy/list',
                'POST v1/strategy/one' => 'v1/strategy/one',
                'POST v1/strategy/usershare' => 'v1/strategy/usershare',
                'POST v1/strategy/subscribe' => 'v1/strategy/subscribe',
            ],
        ],
        
    ],
    'params' => $params,
];

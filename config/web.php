<?php
use kartik\mpdf\Pdf;
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Makarim2016',
        ],
		'PDF2Text' => [
            'class' => 'app\components\PDF2Text',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
		'pdf' => [
			'class' => Pdf::classname(),
			'format' => Pdf::FORMAT_A4,
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'destination' => Pdf::DEST_BROWSER,
			// refer settings section for all configuration options
		],
        'log' => [
            // 'traceLevel' => YII_DEBUG ? 3 : 0,
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    // 'levels' => ['error','info','trace'],
                    'levels' => ['error'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
	'modules' => [
        'gridview' => [
            'class' => 'kartik\grid\Module',
        ],
		'user' => [
			'class' => 'dektrium\user\Module',
			'admins' => ['admin','anggi.alun','festyari','dermawan'],
			'enableRegistration'=>false,
			'enableConfirmation'=>false
		],
		'pdfjs' => [
			'class' => '\yii2assets\pdfjs\Module',
		],
		'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',
 
            // format settings for displaying each date attribute
            'displaySettings' => [
                'date' => 'd-M-Y',
                'time' => 'H:i:s',
                'datetime' => 'd-M-Y H:i:s',
            ],
 
            // format settings for saving each date attribute
            'saveSettings' => [
                'date' => 'Y-M-d', 
                'time' => 'H:i:s',
                'datetime' => 'Y-M-d H:i:s',
            ],
 
 
 
            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,
 
        ],
		'markdown' => [
			'class' => 'kartik\markdown\Module',
		]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii']['class'] = 'yii\gii\Module';
	$config['modules']['gii']['generators'] = [
        'kartikgii-crud' => ['class' => 'warrence\kartikgii\crud\Generator'],
    ];
}

return $config;

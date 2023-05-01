<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'ru',
    'sourceLanguage' => 'ru',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Europe/Moscow',
    'bootstrap' => [],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
	        'class' => 'yii\rbac\PhpManager',
	        'defaultRoles' => [
		        'user',
		        'admin',
	        ],
	        'itemFile' => '@backend/config/rbac/data/items.php',
	        'assignmentFile' => '@backend/config/rbac/data/assignments.php',
	        'ruleFile' => '@backend/config/rbac/data/rules.php',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'common' => 'common.php'
                    ],
                ],
            ],
        ],
        'test' => [
            'test' => 'test'
        ]
    ],
];
?>
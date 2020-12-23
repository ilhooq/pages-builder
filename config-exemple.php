<?php
return [
    'basePath' => __DIR__,
    'defaultLayoutPath' => '@app/modules/site/layouts',
    'defaultLayout' => 'main',
    'blocksPath' => '@app/blocks',
    'errorRoute' => 'site/default/error',
    'language' => 'en',
    'siteName' => 'Your company',
    'contactEmail' => 'contact@domain.com',
    'users' => [
        [
            'id' => 1,
            'username' => 'admin',
            'password' => 'admin'
        ],
    ],
    'menus' => [
        'main' => 'Main menu',
        'footer' => 'Footer menu',
    ],
    'components' => [
        'router' => [
            'class' => 'piko\Router',
            'routes' => [
                '^/admin$' => 'pages/admin/pages',
                '^/login$' => 'site/default/login',
                '^/logout$' => 'site/default/logout',
                '^/install$' => 'pages/install/process',
                '^/([\w-]*)$' => 'pages/default/view|alias=$1',
                '^/(\w+)/(\w+)/(\w+)' => '$1/$2/$3'
            ],
        ],
        'db' => [
            'class' => 'piko\Db',
            'dsn'   => 'sqlite:' . __DIR__ . '/runtime/db.sqlite'
        ],
        'i18n' => [
            'class' => 'piko\I18n',
            'translations' => [
                'site' => '@app/modules/site/messages',
                'pages' => '@app/modules/pages/messages',
            ]
        ],
        'user' => [
            'class' => 'piko\User',
            'identityClass' => 'app\modules\site\models\User',
        ]
    ],
    'modules' => [
        'site' => 'app\modules\site\Module',
        'pages' => 'app\modules\pages\Module',
    ],
    'bootstrap' => ['site', 'pages'],
];

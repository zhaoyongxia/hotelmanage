<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
//        'mailer' => [
//            //邮件发送
//            'class' => 'yii\swiftmailer\Mailer',
//            'useFileTransport' => false,
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'smtp.qq.com',
//                //todo 改为官方邮箱地址
//                'username' => '379914250@qq.com',
//                'password' => 'rbsxspeuryijbjba',
//                'port' => '465',
//                'encryption' => 'ssl',
//            ],
//            'messageConfig' => [
//                'charset' => 'UTF-8',
//                //todo 改为官方邮箱地址
//                'from' => ['379914250@qq.com' => 'ICOXYZ Mailer']
//            ],
//        ],
    ],
];

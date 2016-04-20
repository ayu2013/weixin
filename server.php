<?php

include __DIR__ . '/vendor/autoload.php'; // 引入 composer 入口文件

use EasyWeChat\Foundation\Application;

$options = [
    'debug'  => true,
    'app_id' => 'wx80e3fb27b08918c1',
    'secret' => '8fe504cbe457876dc156a41086229492',
    'token'  => 'easywechat',

    // 'aes_key' => null, // 可选

    'log' => [
        'level' => 'debug',
        'file'  => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
    ],

    //...
];

$app = new Application($options);

// 将响应输出
$app->server->serve()->send();
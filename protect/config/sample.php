<?php
return [
    'db' => [
        'host' => '127.0.0.1',
        'driver' => 'mysql',
        'username' =>'username',
        'password' =>'password',
        'database' => 'database',
        'charset'=>'utf8',
        'collation'=>'utf8_unicode_ci',
        'prefix'=>'pre_',
    ],
    'debugging' => true,
    'url_pre'  => 'http://127.0.0.1/DZX2.5/',
    'jwt_secret' => 'some hash for jwt',
    'logined_scope' => [
        'comment_create'
    ],
];

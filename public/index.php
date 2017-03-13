<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../vendor/autoload.php';


$config  = require '../protect/config/index.php';

$container  =  new \Slim\Container( [
        'settings' => [
            'db' => $config['db'],
            'displayErrorDetails' => true,
        ]
    ]
);

$container['db']  =  function ($container) {
    $capsule  =  new \Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};

$app = new \Slim\App($container); 

$app->get( '/news/{newsid}[/{page}]','\controller\NewsController:get');
$app->get( '/cate/{catid}[/{page}]','\controller\NewsController:cate');
$app->get( '/forum/{fid}','\controller\ForumController:forum');
$app->get( '/forumlist/{fid}','\controller\ForumController:threadlist');
$app->get( '/thread/{tid}[/{page}]','\controller\ThreadController:get');
/*
/news/{newsid}[/{page}]
/list/{catid}[/{page}]
/comment/{comment}[/{newsid}][/{page}]
*/
// CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$container->get("db");

$app->run();

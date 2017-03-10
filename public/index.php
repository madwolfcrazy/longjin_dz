<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../vendor/autoload.php';


$config  = require '../protect/config/index.php';

$app  =  new \Slim\App($config);

$app->get( '/news/{newsid}[/{page}]','\controller\NewsController:get');
$app->get( '/cate/{catid}[/{page}]','\controller\NewsController:cate');
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

$app->run();

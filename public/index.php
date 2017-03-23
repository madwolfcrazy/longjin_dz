<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


date_default_timezone_set('Asia/Shanghai');


require '../vendor/autoload.php';

$config  = require '../protect/config/index.php';

$container  =  new \Slim\Container( [
        'settings' => [
            'db' => $config['db'],
            'displayErrorDetails' => true,
            'url_pre' => $config['url_pre'],
            'jwt_secret' => $config['jwt_secret'],
            'logined_scope' => $config['logined_scope'],
        ],
    ]
);

$container['db']  =  function ($container) {
    $capsule  =  new \Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};

/*
$container['encoder']  =  function ($container) {
    return new Tuupola\Base62;
};
*/

$container['jwt']  =  function ($container) {
    return new stdClass;
};
$app = new \Slim\App($container); 

$app->get( '/',function($r,$rr) {
        return $rr->withJson([]);
        });
$app->get( '/news/{newsid}[/{page}]','\controller\NewsController:get');
$app->get( '/cate/{catid}[/{page}]','\controller\NewsController:cate');
$app->get( '/forum[/{fid}[/{page}]]','\controller\ForumController:forum');
$app->get( '/forumlist/{fid}[/{page}]','\controller\ForumController:threadlist');
$app->get( '/thread/{tid}[/{page}]','\controller\ThreadController:get');
$app->get( '/comment/{newsid}','\controller\NewsController:comment');
$app->post( '/comment/{newsid:[0-9]+}[/{reply_comment_id:[0-9]+}]','\controller\NewsController:create');
$app->post( '/login','\controller\LoginController:login');
$app->post( '/thread/{fid}','\controller\ThreadController:createThread');
$app->post( '/reply/{tid}','\controller\ThreadController:createPost');
//
include "../protect/routes/token.php";
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
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization,')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

//
$app->add(new \Slim\Middleware\JwtAuthentication( [
    'secret' => $config['jwt_secret'],
    'rules' =>[
        new \Slim\Middleware\JwtAuthentication\RequestPathRule([
            'path' => '/',
            'passthrough' => [
                '/token',
                '/login',
            ]
        ]),
        new \Slim\Middleware\JwtAuthentication\RequestMethodRule([
            'passthrough' => ["OPTIONS"]
        ]),
    ],
    'callback' => function ($request, $response, $arguments) use ($container) {
        $container['jwt']  =  $arguments['decoded'];
    },
]));

$checkProxyHeaders = TRUE;
$trustedProxies = ['10.0.0.1','10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

$container->get("db");

$app->run();

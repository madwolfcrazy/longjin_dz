<?php
use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;
use Tuupola\Base62;
use \Interop\Container\ContainerInterface as ContainerInterface;

$app->post("/token", function ($request, $response, $arguments) {
    $requested_scopes = $request->getParsedBody();
    $valid_scopes = [
        "news.view",
        "cate.view",
        "forum.view",
        "thread.view",
    ];
    if($requested_scopes != '') {
        $scopes = array_filter($requested_scopes, function ($needle) use ($valid_scopes) {
            return in_array($needle, $valid_scopes);
        });
    }else{
        $scopes  =  [];
    }
    $now = new DateTime();
    $future = new DateTime("now +2 hours");
    $server = $request->getServerParams();
    $base62  =  new Base62;
    $jti = $base62->encode(random_bytes(16));
    $payload = [
        "iat" => $now->getTimeStamp(),
        "exp" => $future->getTimeStamp(),
        "jti" => $jti,
        "sub" => @$server["PHP_AUTH_USER"],
        "scope" => $scopes
    ];
    $secret  =  $this->get('settings')['jwt_secret'];
    $token = JWT::encode($payload, $secret, "HS256");
    $data["status"] = "ok";
    $data["token"] = $token;
    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
/* This is just for debugging, not usefull in real life. */
$app->get("/dump", function ($request, $response, $arguments) {
    print_r($this->token);
});
$app->post("/dump", function ($request, $response, $arguments) {
    print_r($this->token);
});
/* This is just for debugging, not usefull in real life. */
$app->get("/info", function ($request, $response, $arguments) {
    phpinfo();
});

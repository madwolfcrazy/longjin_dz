<?php

namespace controller;

use Ramsey\Uuid\Uuid;
use Firebase\JWT\JWT;
use Tuupola\Base62;

class LoginController extends \Controller
{

    /**
      *
      *
      **/
    public function login($request, $response, $args) {
        include '../protect/config/ucenter.php';
        include '../vendor/comsenz/uc_client/src/client.php';
        $argsPost  =  $request->getParsedBody();
        $username  =  isset($argsPost['username']) ? trim($argsPost['username']) : FALSE;
        $password  =  isset($argsPost['password']) ? trim($argsPost['password']) : FALSE;
        if(strtolower(UC_CHARSET) != 'utf-8') {
            $username  =  iconv('utf-8', UC_CHARSET, $username);
        }
        if( $username !== FALSE and $password != FALSE) {
            //do the login process
            $login_result  =  uc_user_login($username, $password);
            if($login_result[0] > 0) {
                if(strtolower(UC_CHARSET) != 'utf-8') {
                    $login_result[1]  =  iconv('utf-8', UC_CHARSET, $login_result[1]);
                }
                //return logined jwt
                $scopes  =  $this->ci->get('settings')['logined_scope'];
                $now     =  new \DateTime();
                $future  =  new \DateTime("now +2 hours");
                $base62  =  new Base62;
                $jti = $base62->encode(random_bytes(16));
                $payload = [
                    "iat" => $now->getTimeStamp(),
                    "exp" => $future->getTimeStamp(),
                    "jti" => $jti,
                    "sub" => 'JWT of '.$login_result[1],
                    "scope"     => $scopes,
                    "user_id"   => $login_result[0],
                    "username"  => $login_result[1],
                ];
                $secret  =  $this->ci->get('settings')['jwt_secret'];
                $token   =  JWT::encode($payload, $secret, "HS256");
                $data["loginResult"]  = "SUCCESS";
                $data["token"]   = $token;
                $data['expire']  = $future->getTimestamp();
                return $response->withJson($data);
            }
        }
        return $response->withJson(['loginResult'=>'FAIL']);
    }
}

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
        $argsPost  =  $request->getParsedBody();
        $username  =  isset($argsPost['username']) ? trim($argsPost['username']) : FALSE;
        $password  =  isset($argsPost['password']) ? trim($argsPost['password']) : FALSE;
        if( $username !== FALSE and $password != FALSE) {
            //do the login process
            $monilogin = ['result'=>true, 'username'=>$username,'user_id'=>1998];
            if($monilogin['result']) {
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
                    "sub" => 'JWT of '.$username,
                    "scope"     => $scopes,
                    "user_id"   => $monilogin['user_id'],
                    "username"  => $username,
                ];
                $secret  =  $this->ci->get('settings')['jwt_secret'];
                $token   =  JWT::encode($payload, $secret, "HS256");
                $data["status"]  = "ok";
                $data["token"]   = $token;
                $data['expire']  = $future->getTimestamp();
                return $response->withJson($data);
            }
        }
    }
}

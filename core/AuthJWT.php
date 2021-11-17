<?php
namespace backint\core;
require_once('./config/config.php');
require_once('./vendor/autoload.php');

use Exception;
use Firebase\JWT\JWT;

class AuthJWT {

    /**
     * Generate a JWT using an associative array
     * 
     * @param array assoc $info
     */
    public function generateJWT($info) {
        $time = time();

        $token = array(
            "iat" => $time,
            "exp" => $time + (JWT_EXPIRED_MINUTES * 60),
            "data" => $info
        );

        $jwt = JWT::encode($token, JWT_KEY, JWT_ENCRYPT);

        return $jwt;
    }

    /**
     * Check if a token is valid
     * 
     * @param string $token
     * 
     * @return object
     * 
     * @return null
     */
    public function checkToken($token) {
        try {
            $decode = JWT::decode(
                $token,
                JWT_KEY,
                array(JWT_ENCRYPT)
            );
            return $decode;
        } catch(Exception $ex) {
            return null;
        }
    }
}
?>
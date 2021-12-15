<?php
namespace backint\core;
require_once('./vendor/autoload.php');

use Configuration;
use Exception;
use Firebase\JWT\JWT;

class AuthJWT {

    /**
     * Generate a JWT using an associative array
     * 
     * @param array assoc $info
     */
    public static function generateJWT($info) {
        $time = time();

        $token = array(
            "iat" => $time,
            "exp" => $time + (Configuration::JWT_EXPIRED_MINUTES * 60),
            "data" => $info
        );

        $jwt = JWT::encode($token, Configuration::JWT_KEY, Configuration::JWT_ENCRYPT);

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
    public static function checkToken($token) {
        try {
            $decode = JWT::decode(
                $token,
                Configuration::JWT_KEY,
                array(Configuration::JWT_ENCRYPT)
            );
            return $decode;
        } catch(Exception $ex) {
            return null;
        }
    }
}
?>
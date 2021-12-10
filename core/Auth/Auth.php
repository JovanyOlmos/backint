<?php
namespace backint\core;

use Configuration;
use Policies;

class Auth {

    /**
     * Check if credentials are correct
     * 
     * @param string $user
     * 
     * @param string $pass
     * 
     * @param string $method
     * 
     * @return bool
     */
    public static function checkCredentials($user, $pass, $method) {
        if(Configuration::AUTH_ACTIVE) {
            foreach (AUTH as $value) {
                if($value["username"] == $user && $value["password"] == $pass && self::validLevel($method, $value["level"])) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    /**
     * Get if has any permission
     * 
     * @param string $method
     * 
     * @param string $level
     * 
     * @return bool
     */
    private static function validLevel($method, $level) {
        if(($method == "GET" || $method == "PATCH") && ($level == Policies::READ || $level == Policies::READ_WRITE || $level == Policies::READ_DELETE || $level == Policies::ALL))
            return true;
        if(($method == "POST" || $method == "PUT") && ($level == Policies::WRITE || $level == Policies::READ_WRITE || $level == Policies::WRITE_DELETE || $level == Policies::ALL))
            return true;
        if($method == "DELETE" && ($level == Policies::DELETE || $level == Policies::READ_DELETE || $level == Policies::WRITE_DELETE || $level == Policies::ALL))
            return true;
        return false;
    }
}
?>
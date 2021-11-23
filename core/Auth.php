<?php
namespace backint\core;
require_once("./config/auth-credentials.php");
require_once("./config/config.php");

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
        if(AUTH_ACTIVE) {
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
        if(($method == "GET" || $method == "PATCH") && ($level == READ || $level == READ_WRITE || $level == READ_DELETE || $level == ALL))
            return true;
        if(($method == "POST" || $method == "PUT") && ($level == WRITE || $level == READ_WRITE || $level == WRITE_DELETE || $level == ALL))
            return true;
        if($method == "DELETE" && ($level == DELETE || $level == READ_DELETE || $level == WRITE_DELETE || $level == ALL))
            return true;
        return false;
    }
}
?>
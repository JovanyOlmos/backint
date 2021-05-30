<?php
namespace backint\core;
require_once("./config/auth-credentials.php");
require_once("./config/config.php");
class Auth {
    public function checkCredentials($user, $pass, $method) {
        if(AUTH_ACTIVE) {
            foreach (AUTH as $key => $value) {
                if($value["username"] == $user && $value["password"] == $pass && $this->validLevel($method, $value["level"])) {
                    return true;
                }
            }
            return false;
        } else {
            return true;
        }
    }

    private function validLevel($method, $level) {
        if(($method == "GET" || $method == "PATCH") && ($level == READ || $level == READ_WRITE || $level == READ_DELETE || $level == ALL))
            return true;
        elseif(($method == "POST" || $method == "PUT") && ($level == WRITE || $level == READ_WRITE || $level == WRITE_DELETE || $level == ALL))
            return true;
        elseif($method == "DELETE" && ($level == DELETE || $level == READ_DELETE || $level == WRITE_DELETE || $level == ALL))
            return true;
        else
            return false;
    }
}
?>
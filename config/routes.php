<?php
require_once("./config/path-definitions/get-routes.php");
require_once("./config/path-definitions/delete-routes.php");
require_once("./config/path-definitions/post-routes.php");
require_once("./config/path-definitions/put-routes.php");
require_once("./config/path-definitions/patch-routes.php");
define("ROUTES", array(
    "PUT" => PUT_ROUTES,
    "POST" => POST_ROUTES,
    "GET" => GET_ROUTES,
    "DELETE" => DELETE_ROUTES,
    "PATCH" => PATCH_ROUTES,
));
?>
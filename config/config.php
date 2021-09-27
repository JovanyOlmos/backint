<?php
require_once("./definitions/SQLFormat.php");

/* FOLDER CONFIGURATION */
define("ROUTE_INDEX", "backint/");

/* DATABASE CONFIGURATION */
define("DATABASE_NAME", "vestimenta");
define("DATABASE_HOST", "localhost");
define("DATABASE_USER", "root");
define("DATABASE_PASSWORD", "");

/* SECURITY CONFIGURATION */
define("ALLOWED_HEADERS", "*");
define("ALLOWED_METHODS", array(
    "GET",
    "PUT",
    "OPTIONS",
    "DELETE",
    "POST"
));
define("ALLOWED_ORIGINS", "*");
define("API_KEY", "1_6+n!@ST1C@3Kpr11nk7");

/* CHARSET */
define("DEFAULT_CHARSET", "UTF-8");

/* AUTH */
define("AUTH_ACTIVE", false);
define("READ", "1");
define("WRITE", "2");
define("DELETE", "3");
define("READ_WRITE", "4");
define("READ_DELETE", "5");
define("WRITE_DELETE", "6");
define("ALL", "7");
?>
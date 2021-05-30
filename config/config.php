<?php
require_once("./definitions/SQLFormat.php");

/* FOLDER CONFIGURATION */
define("ROUTE_INDEX", "backint/");

/* CONFIGURATION HELPER FRAMEWORK CONFIGURATION */
define("TABLE_CONFIG_PREFIX", "conf");
define("ROUTE_CONFIG", "config");
define("TABLE_CONFIG_STRUCTURE", array(
    array(
        "name" => "fieldName",
        "type" => VARCHAR,
        "size" => "(50)"
    ),
    array(
        "name" => "label_es",
        "type" => VARCHAR,
        "size" => "(50)"
    ),
    array(
        "name" => "label_en",
        "type" => VARCHAR,
        "size" => "(50)"
    ),
    array(
        "name" => "type",
        "type" => VARCHAR,
        "size" => "(20)"
    ),
    array(
        "name" => "required",
        "type" => BOOLEAN,
        "size" => ""
    ),
    array(
        "name" => "visible",
        "type" => BOOLEAN,
        "size" => ""
    ),
    array(
        "name" => "enabled",
        "type" => BOOLEAN,
        "size" => ""
    )
));

/* DATABASE CONFIGURATION */
define("DATABASE", "backint");
define("HOST", "localhost");
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
define("AUTH_ACTIVE", true);
define("READ", "1");
define("WRITE", "2");
define("DELETE", "3");
define("READ_WRITE", "4");
define("READ_DELETE", "5");
define("WRITE_DELETE", "6");
define("ALL", "7");
?>
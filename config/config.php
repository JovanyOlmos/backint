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
        "type" => VARCHAR
    ),
    array(
        "name" => "label_es",
        "type" => VARCHAR
    ),
    array(
        "name" => "label_en",
        "type" => VARCHAR
    ),
    array(
        "name" => "type",
        "type" => VARCHAR
    ),
    array(
        "name" => "required",
        "type" => BOOLEAN
    ),
    array(
        "name" => "visible",
        "type" => BOOLEAN
    ),
    array(
        "name" => "enabled",
        "type" => BOOLEAN
    )
));

/* SPECIAL ROUTES CONFIGURATIONS */
define("SPECIAL_ROUTES", array(
    "REPORTER" => "rpt"
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
?>
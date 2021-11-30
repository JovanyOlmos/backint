<?php
/* FOLDER CONFIGURATION */
define("ROUTE_INDEX", "backint/");

/* DATABASE CONFIGURATION */
define("DATABASE_NAME", "backint");
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

/* BASIC AUTH */
define("AUTH_ACTIVE", false);

/**
 * JWT Auth Config
 */
define("AUTH_JWT_ACTIVE", false);
define("JWT_KEY", "J2IA;D.-id28=i926?s");
define("JWT_ENCRYPT", "HS512");
define("JWT_EXPIRED_MINUTES", 30);
?>
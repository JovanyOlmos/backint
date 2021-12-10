<?php
class Configuration {
    /**
     * Server folder
     * 
     * @var string
     */
    const ROUTE_INDEX = "backint/";

    /**
     * Database name
     * 
     * @var string
     */
    const DATABASE_NAME = "backint";

    /**
     * Database host
     * 
     * @var string
     */
    const DATABASE_HOST = "localhost";

    /**
     * Database user
     * 
     * @var string
     */
    const DATABASE_USER = "root";

    /**
     * Database password
     * 
     * @var string
     */
    const DATABASE_PASSWORD = "";

    /**
     * Allowed headers
     * 
     * @var string
     */
    const ALLOWED_HEADERS = "*";

    /**
     * Allowed methods. Write without white space between.
     * 
     * @var string
     */
    const ALLOWED_METHODS = "GET,PUT,OPTIONS,POST,PATCH,DELETE";
    
    /**
     * Allowed origins
     * 
     * @var string
     */
    const ALLOWED_ORIGINS = "*";

    /**
     * Server key. Pass by header api-key
     * 
     * @var string
     */
    const API_KEY = "1_6+n!@ST1C@3Kpr11nk7";

    /**
     * Charset configuration
     * 
     * @var string
     */
    const DEFAULT_CHARSET = "UTF-8";

    /**
     * Set basic authentication
     * 
     * @var bool
     */
    const AUTH_ACTIVE = false;

    /**
     * Set JWT Auth
     * 
     * @var bool
     */
    const AUTH_JWT_ACTIVE = false;

    /**
     * Key to crypt JWT
     * 
     * @var string
     */
    const JWT_KEY = "J2IA;D.-id28=i926?s";

    /**
     * Crypt algorithm
     * 
     * @var string
     */
    const JWT_ENCRYPT = "HS512";

    /**
     * Expiration time for JWT (minutes)
     * 
     * @var int
     */
    const JWT_EXPIRED_MINUTES = 30;
}
?>
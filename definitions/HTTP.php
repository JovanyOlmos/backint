<?php
    define("CONTINUE", "100");
    define("SWITCHING_PROTOCOL", "101");
    define("OK", "200");
    define("CREATED", "201");
    define("ACCEPTED", "202");
    define("NO_CONTENT", "204");
    define("RESET_CONTENT", "205");
    define("PARTIAL_CONTENT", "206");
    define("MULTIPLE_CHOISE", "300");
    define("MOVED_PERMANENTLY", "301");
    define("BAD_REQUEST", "400");
    define("UNAUTHORIZED", "401");
    define("FORBIDDEN", "403");
    define("NOT_FOUND", "404");
    define("METHOD_NOT_ALLOWED", "405");
    define("REQUEST_TIME_OUT", "408");
    define("CONFILCT", "409");
    define("INTERNAL_SERVER_ERROR", "500");
    //HTTP Meaning
    define("HTTP_MESSAGE", array(
        100 => "CONTINUE",
        101 => "SWITCHING PROTOCOL",
        200 => "OK",
        201 => "CREATED",
        202 => "ACCEPTED",
        204 => "NO CONTENT",
        205 => "RESET CONTENT",
        206 => "PARTIAL CONTENT",
        300 => "MULTIPLE CONTENT",
        301 => "MOVED PERMANENTLY",
        400 => "BAD REQUEST",
        401 => "UNAUTHORIZED",
        403 => "FORBIDDEN",
        404 => "NOT FOUND",
        405 => "METHOD NOT ALLOWED",
        408 => "REQUEST TIME OUT",
        409 => "CONFLICT",
        500 => "INTERNAL SERVER ERROR"));
?>
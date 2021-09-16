<?php
//Define your routes right here
define("ROUTES", array(
    array(
        "route" => "test",
        "type" => "POST",
        "class" => "APIModelTest",
        "function" => "create"
    ),
    array(
        "route" => "test",
        "type" => "PUT",
        "class" => "APIModelTest",
        "function" => "update"
    ),
    array(
        "route" => "test",
        "type" => "GET",
        "class" => "APIModelTest",
        "function" => "getById"
    ),
    array(
        "route" => "test",
        "type" => "DELETE",
        "class" => "APIModelTest",
        "function" => "deleteById"
    ),
));
?>
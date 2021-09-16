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
    array(
        "route" => "testing",
        "type" => "POST",
        "class" => "APIModelTesting",
        "function" => "create"
    ),
    array(
        "route" => "testing",
        "type" => "PUT",
        "class" => "APIModelTesting",
        "function" => "update"
    ),
    array(
        "route" => "testing",
        "type" => "GET",
        "class" => "APIModelTesting",
        "function" => "getById"
    ),
    array(
        "route" => "testing",
        "type" => "DELETE",
        "class" => "APIModelTesting",
        "function" => "deleteById"
    ),
));
?>
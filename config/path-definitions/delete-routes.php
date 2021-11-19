<?php
define("DELETE_ROUTES", array(
    "categories" => array(
        "class" => "APIModelCategories",
        "function" => "deleteById",
        "jwt" => true
    ),
    "models" => array(
        "class" => "APIModelModels",
        "function" => "deleteById",
        "jwt" => true
    ),
    "phases" => array(
        "class" => "APIModelPhases",
        "function" => "deleteById",
        "jwt" => true
    ),
    "sizes" => array(
        "class" => "APIModelSizes",
        "function" => "deleteById",
        "jwt" => true
    ),
    "shippers" => array(
        "class" => "APIModelShippers",
        "function" => "deleteById",
        "jwt" => true
    ),
    "brands" => array(
        "class" => "APIModelBrands",
        "function" => "deleteById",
        "jwt" => true
    ),
    "countries" => array(
        "class" => "APIModelCountries",
        "function" => "deleteById",
        "jwt" => true
    ),
    "colors" => array(
        "class" => "APIModelColors",
        "function" => "deleteById",
        "jwt" => true
    ),
    "genders" => array(
        "class" => "APIModelGenders",
        "function" => "deleteById",
        "jwt" => true
    ),
    "payments" => array(
        "class" => "APIModelPayments",
        "function" => "deleteById",
        "jwt" => true
    ),
))
?>
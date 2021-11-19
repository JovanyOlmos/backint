<?php
define("POST_ROUTES", array(
    "login" => array(
        "class" => "APIModelUsers",
        "function" => "login",
        "jwt" => false
    ),
    "users" => array(
        "class" => "APIModelUsers",
        "function" => "create",
        "jwt" => false
    ),
    "check-user-exists" => array(
        "class" => "APIModelUsers",
        "function" => "checkUserInfoDoesNotExist",
        "jwt" => false
    ),
    "categories" => array(
        "class" => "APIModelCategories",
        "function" => "create",
        "jwt" => true
    ),
    "models" => array(
        "class" => "APIModelModels",
        "function" => "create",
        "jwt" => true
    ),
    "phases" => array(
        "class" => "APIModelPhases",
        "function" => "create",
        "jwt" => true
    ),
    "sizes" => array(
        "class" => "APIModelSizes",
        "function" => "create",
        "jwt" => true
    ),
    "shippers" => array(
        "class" => "APIModelShippers",
        "function" => "create",
        "jwt" => true
    ),
    "brands" => array(
        "class" => "APIModelBrands",
        "function" => "create",
        "jwt" => true
    ),
    "countries" => array(
        "class" => "APIModelCountries",
        "function" => "create",
        "jwt" => true
    ),
    "products" => array(
        "class" => "APIModelProducts",
        "function" => "create",
        "jwt" => true
    ),
    "colors" => array(
        "class" => "APIModelColors",
        "function" => "create",
        "jwt" => true
    ),
    "productfeatures" => array(
        "class" => "APIModelProductfeatures",
        "function" => "create",
        "jwt" => true
    ),
    "genders" => array(
        "class" => "APIModelGenders",
        "function" => "create",
        "jwt" => true
    ),
    "payments" => array(
        "class" => "APIModelPayments",
        "function" => "create",
        "jwt" => true
    ),
    "customers" => array(
        "class" => "APIModelCustomers",
        "function" => "create",
        "jwt" => true
    ),
    "suppliers" => array(
        "class" => "APIModelSuppliers",
        "function" => "create",
        "jwt" => true
    ),
));
?>
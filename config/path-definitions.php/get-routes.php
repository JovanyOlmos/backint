<?php
define("GET_ROUTES", array(
    "users" => array(
        "class" => "APIModelUsers",
        "function" => "getById",
        "jwt" => true
    ),
    "check-status" => array(
        "class" => "APIModelUsers",
        "function" => "checkProfileStatus",
        "jwt" => true
    ),
    "categories" => array(
        "class" => "APIModelCategories",
        "function" => "getAll",
        "jwt" => true
    ),
    "get-category" => array(
        "class" => "APIModelCategories",
        "function" => "getById",
        "jwt" => true
    ),
    "models" => array(
        "class" => "APIModelModels",
        "function" => "getAllModels",
        "jwt" => true
    ),
    "models-by-category" => array(
        "class" => "APIModelModels",
        "function" => "getByCategoryId",
        "jwt" => true
    ),
    "phases" => array(
        "class" => "APIModelPhases",
        "function" => "getAllPhases",
        "jwt" => true
    ),
    "sizes" => array(
        "class" => "APIModelSizes",
        "function" => "getAllSizes",
        "jwt" => true
    ),
    "shippers" => array(
        "class" => "APIModelShippers",
        "function" => "getAllShippers",
        "jwt" => true
    ),
    "brands" => array(
        "class" => "APIModelBrands",
        "function" => "getAllBrands",
        "jwt" => true
    ),
    "brands-by-supplier" => array(
        "class" => "APIModelBrands",
        "function" => "getBrandsBySupplier",
        "jwt" => true
    ),
    "countries" => array(
        "class" => "APIModelCountries",
        "function" => "getAllCountries",
        "jwt" => true
    ),
    "products" => array(
        "class" => "APIModelProducts",
        "function" => "getAllProducts",
        "jwt" => true
    ),
    "products-by-user" => array(
        "class" => "APIModelProducts",
        "function" => "getSupplierProducts",
        "jwt" => true
    ),
    "colors" => array(
        "class" => "APIModelColors",
        "function" => "getAllColors",
        "jwt" => true
    ),
    "productfeatures" => array(
        "class" => "APIModelProductfeatures",
        "function" => "getAllProductFeatures",
        "jwt" => true
    ),
    "genders" => array(
        "class" => "APIModelGenders",
        "function" => "getGenders",
        "jwt" => true
    ),
    "payments" => array(
        "class" => "APIModelPayments",
        "function" => "getAllPayments",
        "jwt" => true
    ),
    "get-full-info-customer" => array(
        "class" => "APIModelCustomers",
        "function" => "getCustomerByUserId",
        "jwt" => true
    ),
    "supplier-user-id" => array(
        "class" => "APIModelSuppliers",
        "function" => "getByUserId",
        "jwt" => false
    ),
));
?>
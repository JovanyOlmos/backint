<?php
require("./config/config.php");
require("./enums/Policies.php");
require("./enums/SQLFormat.php");
require("./core/Auth/AuthJWT.php");
require("./core/ControllerTools/ControllerBase.php");
require("./core/ControllerTools/Model.php");
require("./core/ControllerTools/ModelField.php");
require("./core/Database/iDBObj.php");
require("./core/Database/DBObj.php");
require("./core/ErrorHandler/ErrObj.php");
require("./core/Http/Http.php");
require("./core/Json/Json.php");
require("./core/QueryBuilder/ObjQL.php");
require("./core/QueryBuilder/QueryBuilder.php");
require("./core/QueryBuilder/Interfaces/iQuickQuery.php");
require("./core/QueryBuilder/QuickQuery.php");
require("./server/server.php");
require("./server/handler.php");
require("./server/router.php");

use backint\core\ErrObj;
use backint\core\Http;
use backint\server\server;

function errorHandler($code, $description, $file = null, $line = null, $context = null)
{
    if (!(error_reporting() & $code)) {
        return;
    }
    $errObj = new ErrObj("$description. Linea: $line. Archivo: $file", Http::INTERNAL_SERVER_ERROR);
    $errObj->sendError();
    die();
}

set_error_handler("errorHandler");

new server();
?>
<?php
require("./config/config.php");
require("./core/Auth/AuthJWT.php");
require("./core/MVCTools/ControllerBase.php");
require("./core/MVCTools/Model.php");
require("./core/MVCTools/ModelField.php");
require("./core/ErrorHandler/Result.php");
require("./core/Http/Http.php");
require("./core/Json/JsProp.php");
require("./core/Json/Json.php");
require("./core/Database/iDBObj.php");
require("./core/Database/Defaults/Defaults.php");
require("./core/Database/Types/iType.php");
require("./core/Database/Types/Enums/DateTimeFormatsEnum.php");
require("./core/Database/Types/Enums/NumberSizeEnum.php");
require("./core/Database/Types/Subtypes/iBoolean.php");
require("./core/Database/Types/Subtypes/iDateTime.php");
require("./core/Database/Types/Subtypes/iDecimal.php");
require("./core/Database/Types/Subtypes/iNumber.php");
require("./core/Database/Types/Subtypes/iString.php");
foreach (glob("./core/Database/Types/DBTypes/*.php") as $filename)
{
    require($filename);
}
foreach (glob("./core/Database/QueryBuilder/ext/*.php") as $filename)
{
    require($filename);
}
require("./core/Database/QueryBuilder/QueryBuilder.php");
require("./core/Database/QuickQuery/iQuickQuery.php");

//Load MySQL manager library
require("./core/Database/MySQL/MySQL.php");
foreach (glob("./core/Database/MySQL/QueryBuilder/ext/*.php") as $filename)
{
    require($filename);
}
require("./core/Database/MySQL/QueryBuilder/QueryBuilder.php");
require("./core/Database/MySQL/QuickQuery/QuickQuery.php");
//End MySQL manager library

require("./server/server.php");
require("./server/handler.php");
require("./server/router.php");

use backint\core\Http;
use backint\core\Result;
use backint\server\server;

function errorHandler($code, $description, $file = null, $line = null, $context = null)
{
    if (!(error_reporting() & $code)) {
        return;
    }
    $errObj = new Result("$description. Linea: $line. Archivo: $file", false);
    $errObj->sendResult(Http::INTERNAL_SERVER_ERROR);
    die();
}

set_error_handler("errorHandler");

Server::run();
?>
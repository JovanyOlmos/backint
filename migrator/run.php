<?php
require(__DIR__."/../core/MVCTools/Model.php");
require(__DIR__."/../core/MVCTools/ModelField.php");
require(__DIR__."/../core/ErrorHandler/Result.php");
require(__DIR__."/../config/config.php");
require(__DIR__."/core/Migrator-Engine.php");
require(__DIR__."/core/iMigrator.php");

//MySQL libraries
require(__DIR__."/../core/Database/Defaults/Defaults.php");
require(__DIR__."/../core/Database/Types/iType.php");
require(__DIR__."/../core/Database/Types/Enums/DateTimeFormatsEnum.php");
require(__DIR__."/../core/Database/Types/Enums/NumberSizeEnum.php");
require(__DIR__."/../core/Database/Types/Subtypes/iBoolean.php");
require(__DIR__."/../core/Database/Types/Subtypes/iDateTime.php");
require(__DIR__."/../core/Database/Types/Subtypes/iDecimal.php");
require(__DIR__."/../core/Database/Types/Subtypes/iNumber.php");
require(__DIR__."/../core/Database/Types/Subtypes/iString.php");
foreach (glob(__DIR__."/../core/Database/Types/DBTypes/*.php") as $filename)
{
    require($filename);
}
require(__DIR__."/MySQL/MySQLMigrator.php");

use backint\migrator\MigratorEngine;
use backint\migrator\MySQLMigrator;

MigratorEngine::runUpdates(new MySQLMigrator());
?>
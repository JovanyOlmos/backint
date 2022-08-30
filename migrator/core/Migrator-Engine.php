<?php
namespace backint\migrator;

use backint\core\iDBObj;
use backint\app\models\ModelLiga;

foreach (glob(__DIR__."/../../app/models/*.php") as $filename)
{
    require_once($filename);
}

foreach (glob(__DIR__."/../../app/enums/*.php") as $filename)
{
    require_once($filename);
}

class MigratorEngine {
    /**
     * Log file
     * 
     * @var mixed
     */
    private static $file;

    /**
     * Process all models and execute the migrator. 
     * 
     * @param iDBObj
     */
    public static function runUpdates(iMigrator $migrator) {
        self::$file = fopen(__DIR__."/../logs/".date("Y-m-d").str_replace(":", "-", date("H:i:s")).".log", "w");
        self::addLogMessage("Init binnacle");
        $classesTarget = array();
        foreach(get_declared_classes() as $class)
        {
            if(strpos($class, "backint\\app\\models\\") !== false) {
                $object = new $class();
                array_push($classesTarget, $class);
                $migrator::createTable($object);
            }
        }
        foreach ($classesTarget as $targetClass) {
            $model = new $targetClass();
            $migrator::alterTable($model);
        }
    }

    /**
     * Add a message to log file
     * 
     * @param string $message
     * 
     * @param boolean $isError
     */
    public static function addLogMessage($message, $isError = false) {
        if($isError)
            fwrite(self::$file, "[ERROR] ".date("H:i:s").": ".$message."\n");
        else
            fwrite(self::$file, "[INFO] ".date("H:i:s").": ".$message."\n");
    }
}
?>
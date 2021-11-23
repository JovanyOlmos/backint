<?php
namespace backint\update;

use mysqli;
foreach (glob(__DIR__."/updates/*.php") as $filename)
{
    require_once($filename);
}

class UpdateEngine {
    private static $file;

    public static function runUpdates() {
        self::$file = fopen(__DIR__."/logs/".date("Y-m-d").str_replace(":", "-", date("H:i:s")).".log", "w");
        foreach(get_declared_classes() as $class)
        {
            if(strpos($class, "backint\\update\\") !== false && strpos($class, "backint\\update\\IUpdate") === false) {
                $object = new $class();
                if(is_subclass_of($object, "backint\\update\\IUpdate")) {
                    self::checkLogs($object);
                }
            }
        }
    }

    private static function checkLogs($object) {
        $connection = new mysqli(UPDATE_DB_HOST, UPDATE_DB_USER, UPDATE_DB_PASSWORD, UPDATE_DB_NAME);
        self::addLogMessage("Init binnacle");
        if(mysqli_query($connection, "SELECT * FROM update_logs LIMIT 1;")) {
            self::addLogMessage("Searching update log...");
            if($result = mysqli_query($connection, "SELECT * FROM update_logs WHERE log_name = '".str_replace("\\", "", get_class($object))."';")) {
                self::addLogMessage("SELECT * FROM update_logs WHERE log_name = '".str_replace("\\", "", get_class($object))."';");
                $record = mysqli_fetch_assoc($result);
                if(!is_null($record)) {
                    self::addLogMessage("Checking update version...");
                    if($record["log_version"] < $object->version()) {
                        self::addLogMessage("Current version ".$record["log_version"]." => new version ".$object->version());
                        if(mysqli_query($connection, $object->script())) {
                            if(mysqli_query($connection, "UPDATE update_logs SET log_version = ".$object->version()." WHERE log_name = '".str_replace("\\", "", get_class($object))."';")) {
                                self::addLogMessage("Update '".get_class($object)."' applied successfuly");
                                mysqli_close($connection);
                                return;
                            }
                            self::addLogMessage("Update '".get_class($object)."' ran out correctly, but its version couldn't be updated.", true);
                            mysqli_close($connection);
                            return;
                        }
                        self::addLogMessage("Update failed while running '".get_class($object)."' script =>\n"
                            .$object->script(), true);
                        mysqli_close($connection);
                        die();
                    }
                    self::addLogMessage("Update doesn't need to be run");
                    mysqli_close($connection);
                    return;
                }
                self::addLogMessage("Update log not found in binnacle... Creating log...");
                if($result = mysqli_query($connection, "INSERT INTO update_logs (log_name, log_version) VALUES ('".str_replace("\\", "", get_class($object))."', 0);")) {
                    self::addLogMessage("INSERT INTO update_logs (log_name, log_version) VALUES ('".str_replace("\\", "", get_class($object))."', 0);");
                    self::addLogMessage("Update log created correctly... Init update process");
                    mysqli_close($connection);
                    self::checkLogs($object);
                    return;
                }
                self::addLogMessage("Error to create update log", true);
                die();
            } else {
                self::addLogMessage("Update log not found in binnacle... Creating log...");
                if($result = mysqli_query($connection, "INSERT INTO update_logs (log_name, log_version) VALUES ('".str_replace("\\", "", get_class($object))."', 0);")) {
                    self::addLogMessage("Update log created correctly... Init update process");
                    mysqli_close($connection);
                    self::checkLogs($object);
                    return;
                }
                self::addLogMessage("Error to create update log", true);
                mysqli_close($connection);
                die();
            }
        } else {
            $script = "CREATE TABLE IF NOT EXISTS update_logs (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                log_name VARCHAR(50) NOT NULL,
                log_version INT NOT NULL    
            );";
            if(mysqli_query($connection, $script)) {
                self::addLogMessage("Binnacle created!");
                mysqli_close($connection);
                self::checkLogs($object);
                return;
            } else {
                self::addLogMessage("Error on creating binnacle process", true);
                mysqli_close($connection);
                die();
            }
        }
    }

    /**
     * Add a message to log file
     * 
     * @param string $message
     * 
     * @param boolean $isError
     */
    private static function addLogMessage($message, $isError = false) {
        if($isError)
            fwrite(self::$file, "[ERROR] ".date("H:i:s").": ".$message."\n");
        fwrite(self::$file, "[INFO] ".date("H:i:s").": ".$message."\n");
    }
}
?>
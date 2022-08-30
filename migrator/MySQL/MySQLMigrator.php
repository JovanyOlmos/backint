<?php
namespace backint\migrator;

use backint\core\db\types\DateTimeFormatsEnum;
use backint\core\db\types\iBoolean;
use backint\core\db\types\iDateTime;
use backint\core\db\types\iDecimal;
use backint\core\db\types\iNumber;
use backint\core\db\types\iString;
use backint\core\db\types\NumberSizeEnum;
use backint\core\Model;
use Configuration;
use Error;
use Exception;
use mysqli;
use mysqli_result;

class MySQLMigrator implements iMigrator {

    public static function createTable(Model $model) {
        $query = "CREATE TABLE IF NOT EXISTS `".$model->getModelName()."`(";
        $index = 0;
        if(!is_null($model->getIdField())) {
            $query .= "`".$model->getIdField()->getFieldName()."` ".self::getFormat($model->getIdField()->getFormat())
                ." PRIMARY KEY NOT NULL AUTO_INCREMENT";
            $index = 1;
        }
        foreach ($model->fields as $field) {
            $query .= $index > 0 ? ", " : "";
            $query .= "`".$field->getFieldName()."` ".self::getFormat($field->getFormat())." ".($field->getMandatory() ? "NOT NULL " : "");
            $index++;
        }
        $query .= ");";
        $result = self::executeQuery($query);
        if($result) {
            MigratorEngine::addLogMessage("Table `".$model->getModelName()."` ready.");
        } else {
            MigratorEngine::addLogMessage("Error trying to create table `".$model->getModelName()."` \nQuery:".$query, true);
        }
    }

    public static function alterTable(Model $model) {
        MigratorEngine::addLogMessage("Reading table structure...");
        $query = "DESCRIBE ".$model->getModelName().";";
        $result = self::getFetchAssoc($query);
        if(is_null($result)) {
            MigratorEngine::addLogMessage("Error getting fields from table `".$model->getModelName()."`", true);
        } else {
            $query = "";
            $DBfields = array();
            //Delete old fields
            while($row = mysqli_fetch_assoc($result)) {
                $query .= !key_exists($row["Field"], $model->fields) && $row["Key"] != "PRI"
                    ? "ALTER TABLE `".$model->getModelName()."` DROP COLUMN `".$row["Field"]."`;"
                    : "";
                $DBfields[$row["Field"]] = array("Type" => $row["Type"], "Key" => $row["Key"], "Null" => $row["Null"]);
            }
            //Add primary key
            if(!is_null($model->getIdField()) && !key_exists($model->getIdField()->getFieldName(), $DBfields)) {
                $query .= "ALTER TABLE `".$model->getModelName()."` ADD COLUMN `".$model->getIdField()->getFieldName()."`"
                    ." INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT;";
            }
            //Update model fields
            foreach ($model->fields as $key => $field) {
                $query .= !key_exists($key, $DBfields) 
                    ? "ALTER TABLE `".$model->getModelName()."` ADD COLUMN `".$field->getFieldName()."` ".self::getFormat($field->getFormat())." ".($field->getMandatory() ? " NOT NULL" : " NULL").";"
                    : (strtoupper(self::getFormat($field->getFormat())) != strtoupper($DBfields[$key]["Type"]) || $field->getMandatory() != ($DBfields[$key]["Null"] == "YES" ? false : true) 
                        ? "ALTER TABLE `".$model->getModelName()."` MODIFY `".$field->getFieldName()."` ".self::getFormat($field->getFormat())." ".($field->getMandatory() ? " NOT NULL" : " NULL").";"
                        : "");
                $query .= !is_null($field->getModelLinked()) && !is_null($field->getModelFieldLinked()) && $DBfields[$key]["Key"] != "MUL"
                    ? "ALTER TABLE `".$model->getModelName()."` ADD CONSTRAINT `fk_".$model->getModelName()."_".$field->getFieldName()."_".$field->getModelLinked()->getModelName()."_".$field->getModelFieldLinked()->getFieldName()."` FOREIGN KEY (`".$field->getFieldName()."`) REFERENCES `".$field->getModelLinked()->getModelName()."`(`".$field->getModelFieldLinked()->getFieldName()."`);"
                    : "";
            }
            if($query != "") {
                $queries = explode(";", $query);
                foreach ($queries as $singleQuery) {
                    if($singleQuery != "") {
                        $result = self::executeQuery($singleQuery);
                        if($result) {
                            MigratorEngine::addLogMessage("Table `".$model->getModelName()."` updated correctly \n QUERY: \"$singleQuery\"");
                        } else {
                            MigratorEngine::addLogMessage("Error running alters from table `".$model->getModelName()."` \n QUERY: \"$singleQuery\"", true);
                        }
                    }
                }
            } else {
                MigratorEngine::addLogMessage("Table `".$model->getModelName()."` already up to date");
            }
        }
    }

    /**
     * MySQL connection
     * 
     * @var mysqli
     */
    private static $connection;

    /**
     * Execute a MySql query and get a mysqli_result object. Use it with a fetch_assoc() function.
     * 
     * @param string $query
     * 
     * @return mysqli_result
     */
    private static function getFetchAssoc(string $query) {
        self::initConn();
        try {
            if($result = mysqli_query(self::$connection, $query)) {
                if($result->num_rows > 0)
                {
                    mysqli_close(self::$connection);
                    return $result;
                }
                mysqli_close(self::$connection);
                return null;
            }
            else {
                mysqli_close(self::$connection);
                return null;
            }
        } catch (Exception $th) {
            mysqli_close(self::$connection);
            return null;
        }
    }

    /**
     * Execute query type Update | Delete | Create
     * 
     * @return bool
     */
    private static function executeQuery(string $query) {
        try {
            self::initConn();
            if($result = mysqli_query(self::$connection, $query)) {
                mysqli_close(self::$connection);
                return true;
            }
            mysqli_close(self::$connection);
            return null;
        } catch(Exception $_ex) {
            mysqli_close(self::$connection);
            return null;
        }
    }

    /**
     * Connect to DB. Initialize a MySQLi Object
     */
    private static function initConn() {
        try {
            self::$connection = 
                new mysqli(
                    Configuration::DATABASE_HOST, 
                    Configuration::DATABASE_USER, 
                    Configuration::DATABASE_PASSWORD, 
                    Configuration::DATABASE_NAME
                );
        } catch (Exception  $th) {
            return null;
        }
    }

    private static function getFormat($format) {
        if(get_parent_class($format) == iString::class) {
            return $format->autosize() ? "VARCHAR(".$format->length().")" : "CHAR(".$format->length().")";
        }
        if(get_parent_class($format) == iDateTime::class) {
            if($format->format() == DateTimeFormatsEnum::Date) {
                return "DATE";
            }
            if($format->format() == DateTimeFormatsEnum::DateTime) {
                return "TIMESTAMP";
            }
            if($format->format() == DateTimeFormatsEnum::Time) {
                return "TIME";
            }
            throw new Error("Unkown date type on migration");
        }
        if(get_parent_class($format) == iBoolean::class) {
            return "BOOLEAN";
        }
        if(get_parent_class($format) == iNumber::class) {
            if($format->size() == NumberSizeEnum::LARGE) {
                return "BIGINT".($format->unsigned() ? " UNSIGNED" : "");
            }
            if($format->size() == NumberSizeEnum::MEDIUM) {
                return "INT".($format->unsigned() ? " UNSIGNED" : "");
            }
            if($format->size() == NumberSizeEnum::TINY) {
                return "SMALLINT".($format->unsigned() ? " UNSIGNED" : "");
            }
        }
        if(get_parent_class($format) == iDecimal::class) {
            if($format->simple())
                return "DOUBLE";
            return "DECIMAL(".$format->size().",".$format->decimalLength().")";
        }
        throw new Error("Invalid type on migration");
    }
}
?>
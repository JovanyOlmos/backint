<?php
namespace backint\core;
require_once("./definitions/HTTP.php");
require_once("./core/DBObj.php");
require_once("./core/OInterface.php");
use backint\core\DBObj;
use backint\core\OInterface;
class OController {

    public function __construct() {

    }

    public function register(OInterface $objInterface) {
        $dbObject = new DBObj();
        $sqlQuery = "INSERT INTO ".$objInterface->getDBTableName()." (";
        $sqlValues = "(";
        $index = 0;
        foreach ($objInterface->objectFields as $key => $value) {
            if($index > 0)
            {
                $sqlQuery .= ",";
                $sqlValues .= ",";
            }
            $columnName = $value->getColumnName();
            $fieldValue = $value->fieldValue;
            $sqlQuery .= $columnName;
            if($fieldValue == "")
                $fieldValue = "null";
            if(SQL_FORMAT[$value->getDBFormat()] && $fieldValue != "null")
                $sqlValues .= "'".$fieldValue."'";
            else
                $sqlValues .= $fieldValue;
            $index++;
        }
        $sqlQuery .= ")";
        $sqlValues .= ");";
        $sqlQuery .= " VALUES ".$sqlValues;
        $err = $dbObject->doQuery($sqlQuery);
        return $err;
    }

    public function delete(OInterface $objInterface) {
        $dbObject = new DBObj();
        $sqlQuery = "DELETE FROM ".$objInterface->getDBTableName()." WHERE ".$objInterface->getColumnNameFromIdTable()." = ".$objInterface->getIdObject();
        $err = $dbObject->doQuery($sqlQuery);
        return $err;
    }

    public function put(OInterface $objInterface) {
        $dbObject = new DBObj();
        $sqlQuery = "UPDATE ".$objInterface->getDBTableName()." SET ";
        $index = 0;
        foreach ($objInterface->objectFields as $key => $value) {
            if($index > 0)
            {
                $sqlQuery .= ",";
            }
            $columnName = $value->getColumnName();
            $fieldValue = $value->fieldValue;
            $sqlQuery .= $columnName." = ";
            if($fieldValue == "")
                $fieldValue = "null";
            if(SQL_FORMAT[$value->getDBFormat()] && $fieldValue != "null")
                $sqlQuery .= "'".$fieldValue."'";
            else
                $sqlQuery .= $fieldValue;
            $index++;
        }
        $sqlQuery .= " WHERE ".$objInterface->getColumnNameFromIdTable()." = ".$objInterface->getIdObject().";";
        $err = $dbObject->doQuery($sqlQuery);
        return $err;
    }

    public function fillObjInterfaceById(int $id, OInterface $objInterface) {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$objInterface->getDBTableName()." WHERE ".$objInterface->getColumnNameFromIdTable()." = ".$id.";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        if($doFetch != null)
        {
            while($row = $doFetch->fetch_assoc())
            {
                $objInterface->setIdObject($row[$objInterface->getColumnNameFromIdTable()]);
                foreach ($objInterface->objectFields as $key => $value) {
                    $columnName = $value->getColumnName();
                    if($row[$columnName] != "")
                        $objInterface->objectFields[$columnName]->fieldValue = $row[$columnName];
                    else
                        $objInterface->objectFields[$columnName]->fieldValue = "null";
                }
            }
        }
        else
        {
            $objInterface = null;
        }
        return $objInterface;
    }

    public function getObjInterfaceArrayByForeignId(int $foreignId, string $foreignField, OInterface $objInterface) { //Aun no funciona
        $dbObject = NEW DBObj();
        $sqlQuery = "SELECT * FROM ".$objInterface->getDBTableName()." WHERE ".$foreignField." = ".$foreignId.";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        $objectIndex = 0;
        $interfaceObjects = array();
        if($doFetch != null)
        {
            while($row = $doFetch->fetch_assoc())
            {
                $fieldObjects = array();
                $objInterface->setIdObject($row[$objInterface->getColumnNameFromIdTable()]);
                foreach ($objInterface->objectFields as $key => $value) { //Tal vez crear otro objeto field para que se clone
                    $columnName = $value->getColumnName();
                    
                    if($row[$columnName] != "")
                        $objInterface->objectFields[$columnName]->fieldValue = $row[$columnName];
                    else
                        $objInterface->objectFields[$columnName]->fieldValue = "null";
                    $new[$columnName] = clone $objInterface->objectFields[$columnName];
                }
                $interfaceObjects[$objectIndex] = clone $objInterface;
                $interfaceObjects[$objectIndex]->objectFields = $new;
                $objectIndex++;
            }
        }
        else
        {
            $interfaceObjects = null;
        }
        return $interfaceObjects;
    }
}
?>
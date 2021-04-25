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

    public function register($objInterface) {
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

    public function delete($objInterface) {
        $dbObject = new DBObj();
        $sqlQuery = "DELETE FROM ".$objInterface->getDBTableName()." WHERE ".$objInterface->getColumnNameFromIdTable()." = ".$objInterface->getIdObject();
        $err = $dbObject->doQuery($sqlQuery);
        return $err;
    }

    public function put($objInterface) {
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

    public function get() {
        if(func_num_args() == 2)
            return $this->getSimple(func_get_args()[0], func_get_args()[1]);
        elseif(func_num_args() == 3)
            return $this->getWithFilter(func_get_args()[0], func_get_args()[1], func_get_args()[2]);
        elseif(func_num_args() == 4)
            return $this->getWithFilterAndSort(func_get_args()[0], func_get_args()[1], func_get_args()[2], func_get_args()[3]);
    }

    private function getSimple($id, $objInterface) {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$objInterface->getDBTableName()." WHERE ".$objInterface->getColumnNameFromIdTable()." = ".$id.";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        return $this->fillObject($doFetch, $objInterface);
    }

    private function getWithFilter($id, $objInterface, $filter) {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$objInterface->getDBTableName()." WHERE ".$objInterface->getColumnNameFromIdTable()." = ".$id." ";
        $sqlQuery .= $filter->getFilter().";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        return $this->fillObject($doFetch, $objInterface);
    }

    private function getWithFilterAndSort($id, $objInterface, $filter, $sort) {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$objInterface->getDBTableName()." WHERE ".$objInterface->getColumnNameFromIdTable()." = ".$id." ";
        $sqlQuery .= $filter->getFilter()." ";
        $sqlQuery .= $sort->getFilter().";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        return $this->fillObject($doFetch, $objInterface);
    }

    private function fillObject($doFetch, $objInterface) {
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

    public function getArray() {
        if(func_num_args() == 3)
            return $this->getArrayForeignKey(func_get_args()[0], func_get_args()[1], func_get_args()[2]);
    }

    private function getArrayForeignKey(int $foreignId, string $foreignField, OInterface $objInterface) {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$objInterface->getDBTableName()." WHERE ".$foreignField." = ".$foreignId.";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        return $this->fillObject($doFetch, $objInterface);
    }

    private function fillObjects($doFetch, $objInterface) {
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
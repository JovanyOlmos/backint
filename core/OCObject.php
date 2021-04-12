<?php
namespace backint\core;
require_once("./core/DBObject.php");
require_once("./core/OIObject.php");
use backint\core\DBObject;
use backint\core\OIObject;
class OCObject {
    //private $sessionParams;

    public function __construct() {
        //$this->sessionParams = session_start();
    }

    public function register(OIObject $oiObject) {
        $dbObject = new DBObject();
        $sqlQuery = "INSERT INTO ".$oiObject->getDBTableName()." (";
        $sqlValues = "(";
        $index = 0;
        foreach ($oiObject->getFields() as $key => $value) {
            if($index > 0)
            {
                $sqlQuery .= ",";
                $sqlValues .= ",";
            }
            $columnName = $value->getColumnName();
            $fieldValue = $value->getFieldValue();
            $sqlQuery .= $columnName;
            if($fieldValue == "")
                $fieldValue = "null";
            if($value->getDBFormat() && $fieldValue != "null")
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

    public function delete(OIObject $oiObject) {
        $dbObject = new DBObject();
        $sqlQuery = "DELETE FROM ".$oiObject->getDBTableName()." WHERE ".$oiObject->getColumnNameFromIdTable()." = ".$oiObject->getIdObject();
        $err = $dbObject->doQuery($sqlQuery);
        return $err;
    }

    public function put(OIObject $oiObject) {
        $dbObject = new DBObject();
        $sqlQuery = "UPDATE ".$oiObject->getDBTableName()." SET ";
        $index = 0;
        foreach ($oiObject->getFields() as $key => $value) {
            if($index > 0)
            {
                $sqlQuery .= ",";
            }
            $columnName = $value->getColumnName();
            $fieldValue = $value->getFieldValue();
            $sqlQuery .= $columnName." = ";
            if($fieldValue == "")
                $fieldValue = "null";
            if($value->getDBFormat() && $fieldValue != "null")
                $sqlQuery .= "'".$fieldValue."'";
            else
                $sqlQuery .= $fieldValue;
            $index++;
        }
        $sqlQuery .= " WHERE ".$oiObject->getColumnNameFromIdTable()." = ".$oiObject->getIdObject().";";
        $err = $dbObject->doQuery($sqlQuery);
        return $err;
    }

    public function fillOIObjectById(int $id, OIObject $oiObject) {
        $dbObject = new DBObject();
        $sqlQuery = "SELECT * FROM ".$oiObject->getDBTableName()." WHERE ".$oiObject->getColumnNameFromIdTable()." = ".$id.";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        while($row = $doFetch->fetch_assoc())
        {
            $oiObject->setIdObject($row[$oiObject->getColumnNameFromIdTable()]);
            foreach ($oiObject->getFields() as $key => $value) {
                $columnName = $value->getColumnName();
                if($row[$columnName] != "")
                    $oiObject->getFields()[$columnName]->setFieldValue($row[$columnName]);
                else
                    $oiObject->getFields()[$columnName]->setFieldValue("null");
            }
        }
        return $oiObject;
    }

    public function getOIObjectArrayByForeignId(int $foreignId, string $foreignField, OIObject $oiObject) { //Aun no funciona
        $dbObject = NEW DBObject();
        $sqlQuery = "SELECT * FROM ".$oiObject->getDBTableName()." WHERE ".$foreignField." = ".$foreignId.";";
        echo $sqlQuery;
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        $objectIndex = 0;
        $oiObjects = array();
        while($row = $doFetch->fetch_assoc())
        {
            $oiObject->setIdObject($row[$oiObject->getColumnNameFromIdTable()]);
            foreach ($oiObject->getFields() as $key => $value) { //Tal vez crear otro objeto field para que se clone
                $columnName = $value->getColumnName();
                
                if($row[$columnName] != "")
                    $oiObject->getFields()[$columnName]->setFieldValue($row[$columnName]);
                else
                    $oiObject->getFields()[$columnName]->setFieldValue("null");
                $oiObject->getFields()[$columnName]->setFieldValue(clone $oiObject->getFields()[$columnName]->getFieldValue());
            }
            $oiObjects[$objectIndex] = clone $oiObject;
            $objectIndex++;
        }
        return $oiObjects;
    }
}
?>
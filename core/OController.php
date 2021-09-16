<?php
namespace backint\core;
require_once("./definitions/HTTP.php");
require_once("./core/DBObj.php");
require_once("./core/OInterface.php");
require_once("./core/ErrObj.php");
require_once("./core/http.php");
use backint\core\Http;
use backint\core\DBObj;
use backint\core\ErrObj;
use backint\core\OInterface;
use Exception;

class OController {

    /**
     * Constructor
     */
    public function __construct() {

    }

    /**
     * Make an insert into a table
     * 
     * @param OInterface $objInterface
     * 
     * @return ErrObj
     */
    public function insert($objInterface) {
        try {
            $dbObject = new DBObj();
            $sqlQuery = "INSERT INTO ".$objInterface->getTableName()." (";
            $sqlValues = "(";
            $index = 0;
            foreach ($objInterface->fields as $key => $value) {
                if($index > 0)
                {
                    $sqlQuery .= ",";
                    $sqlValues .= ",";
                }
                $columnName = $key;
                $fieldValue = $value->value;
                $sqlQuery .= $columnName;
                if($fieldValue == "")
                    $fieldValue = "null";
                if(SQL_FORMAT[$value->getFormat()] && $fieldValue != "null")
                    $sqlValues .= "'".$fieldValue."'";
                else
                    $sqlValues .= $fieldValue;
                $index++;
            }
            $sqlQuery .= ")";
            $sqlValues .= ");";
            $sqlQuery .= " VALUES ".$sqlValues;
            $err = $dbObject->doQuery($sqlQuery);
        } catch (Exception $ex) {
            $err = new ErrObj($ex, Http::$HTTP_CONFILCT);
        }
        return $err;
    }

    /**
     * Make a delete in a table using a OInterface with an PK Id previously set
     * 
     * @param OInterface $objInterface
     * 
     * @return ErrObj
     */
    public function delete($objInterface) {
        try {
            $dbObject = new DBObj();
            $sqlQuery = "DELETE FROM ".$objInterface->getTableName()." WHERE ".$objInterface->getPKFieldName()." = ".$objInterface->getPKValue();
            $err = $dbObject->doQuery($sqlQuery);
        } catch (Exception $ex) {
            $err = new ErrObj($ex, Http::$HTTP_CONFILCT);
        }
        return $err;
    }

    /**
     * Update a record from Database using an OInterface as new values
     * 
     * @param OInterface
     * 
     * @return ErrObj
     */
    public function update($objInterface) {
        try {
            $dbObject = new DBObj();
            $sqlQuery = "UPDATE ".$objInterface->getTableName()." SET ";
            $index = 0;
            foreach ($objInterface->fields as $key => $value) {
                if($index > 0)
                {
                    $sqlQuery .= ",";
                }
                $columnName = $key;
                $fieldValue = $value->value;
                $sqlQuery .= $columnName." = ";
                if($fieldValue == "")
                    $fieldValue = "null";
                if(SQL_FORMAT[$value->getFormat()] && $fieldValue != "null")
                    $sqlQuery .= "'".$fieldValue."'";
                else
                    $sqlQuery .= $fieldValue;
                $index++;
            }
            $sqlQuery .= " WHERE ".$objInterface->getPKFieldName()." = ".$objInterface->getPKValue().";";
            $err = $dbObject->doQuery($sqlQuery);
        } catch (Exception $ex) {
            $err = new ErrObj($ex, Http::$HTTP_CONFILCT);
        }
        return $err;
    }

    /**
     * Make a select and bring just one record
     * 
     * @param OInterface $objInterface
     * 
     * @param SQLControllerHandler $handlers
     * 
     * @return OInterface
     * 
     * @return null
     */
    public function selectSimple($objInterface, $handlers) {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$objInterface->getTableName().""
            .$handlers->getControllerFilter()->getFilter()
            .$handlers->getControllerOrder()->getSort()." LIMIT 1;";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        return $this->fillObject($doFetch, $objInterface);
    }

    /**
     * Fill an OInterface using a MySQLi result object
     * 
     * @param mysqli_result $doFetch
     *  
     * @param OInterface $objInterface
     * 
     * @return OInterface
     * 
     * @return null
     */
    private function fillObject($doFetch, $objInterface) {
        if($doFetch != null)
        {
            while($row = $doFetch->fetch_assoc())
            {
                $objInterface->setPKValue($row[$objInterface->getPKFieldName()]);
                foreach ($objInterface->fields as $key => $value) {
                    $columnName = $value->getColumnName();
                    if($row[$columnName] != "")
                        $objInterface->fields[$columnName]->value = $row[$columnName];
                    else
                        $objInterface->fields[$columnName]->value = "null";
                }
            }
        }
        else
        {
            $objInterface = null;
        }
        return $objInterface;
    }

    /**
     * Make a select and bring all records using handlers to filter
     * 
     * @param OInterface $objInterface
     * 
     * @param SQLControllerHandler $handlers
     * 
     * @return array OInterface
     * 
     * @return null
     */
    public function selectMultiple($objInterface, $handlers) {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$objInterface->getTableName().""
            .$handlers->getControllerFilter()->getFilter()
            .$handlers->getControllerOrder()->getSort().";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        return $this->fillObjects($doFetch, $objInterface);
    }

    /**
     * Fill an OInterface array using a MySQLi result object
     * 
     * @param mysqli_result $doFetch
     *  
     * @param OInterface $objInterface
     * 
     * @return array OInterface
     * 
     * @return null
     */
    private function fillObjects($doFetch, $objInterface) {
        $objectIndex = 0;
        $interfaceObjects = array();
        if($doFetch != null)
        {
            while($row = $doFetch->fetch_assoc())
            {
                $fieldObjects = array();
                $objInterface->setPKValue($row[$objInterface->getPKFieldName()]);
                foreach ($objInterface->fields as $key => $value) {
                    $columnName = $value->getColumnName();
                    
                    if($row[$columnName] != "")
                        $objInterface->fields[$columnName]->value = $row[$columnName];
                    else
                        $objInterface->fields[$columnName]->value = "null";
                    $new[$columnName] = clone $objInterface->fields[$columnName];
                }
                $interfaceObjects[$objectIndex] = clone $objInterface;
                $interfaceObjects[$objectIndex]->fields = $new;
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
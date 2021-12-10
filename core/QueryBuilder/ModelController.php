<?php
namespace backint\core;

use backint\core\DBObj;
use backint\core\ErrObj;
use backint\core\Model;
use Exception;

class ModelController {

    /**
     * Make an insert into a table
     * 
     * @param Model $objInterface
     * 
     * @return ErrObj
     */
    public static function insert($objInterface) {
        try {
            $dbObject = new DBObj();
            $sqlQuery = "INSERT INTO ".$objInterface->getTableName()." (";
            $sqlValues = "(";
            $index = 0;
            foreach ($objInterface->fields as $key => $ifield) {
                if($index > 0)
                {
                    $sqlQuery .= ",";
                    $sqlValues .= ",";
                }
                $columnName = $key;
                $fieldValue = $ifield->value;
                $sqlQuery .= $columnName;
                $fieldValue = Model::nullPropagation($ifield);
                $sqlValues .= self::convertToSQLFormat($ifield, $fieldValue);
                $index++;
            }
            $sqlQuery .= ")";
            $sqlValues .= ");";
            $sqlQuery .= " VALUES ".$sqlValues;
            $err = $dbObject->doQuery($sqlQuery);
        } catch (Exception $ex) {
            $err = new ErrObj($ex, CONFILCT);
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
    public static function delete($objInterface) {
        try {
            $dbObject = new DBObj();
            $sqlQuery = "DELETE FROM ".$objInterface->getTableName()." WHERE ".$objInterface->getPKFieldName()." = ".$objInterface->getPKValue();
            $err = $dbObject->doQuery($sqlQuery);
        } catch (Exception $ex) {
            $err = new ErrObj($ex, CONFILCT);
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
    public static function update($objInterface) {
        try {
            $dbObject = new DBObj();
            $sqlQuery = "UPDATE ".$objInterface->getTableName()." SET ";
            $index = 0;
            foreach ($objInterface->fields as $key => $iField) {
                if(!is_null($iField->value) && $iField->value != '') {
                    if($index > 0)
                    {
                        $sqlQuery .= ",";
                    }
                    $columnName = $key;
                    $fieldValue = $iField->value;
                    $sqlQuery .= $columnName." = ";
                    $sqlQuery .= self::convertToSQLFormat($iField, $fieldValue);
                    $index++;
                }
            }
            $sqlQuery .= " WHERE ".$objInterface->getPKFieldName()." = ".$objInterface->getPKValue().";";
            $err = $dbObject->doQuery($sqlQuery);
        } catch (Exception $ex) {
            $err = new ErrObj($ex, CONFILCT);
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
    public static function selectSimple($objInterface, $handlers) {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$objInterface->getTableName().""
            .$handlers->where()->getFilter()
            .$handlers->orderBy()->getSort()." LIMIT 1;";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        return self::fillObject($doFetch, $objInterface);
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
    private static function fillObject($doFetch, $objInterface) {
        if($doFetch != null)
        {
            while($row = $doFetch->fetch_assoc())
            {
                $objInterface->setPKValue($row[$objInterface->getPKFieldName()]);
                foreach ($objInterface->fields as $value) {
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
    public static function selectMultiple($objInterface, $handlers) {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$objInterface->getTableName().""
            .$handlers->where()->getFilter()
            .$handlers->orderBy()->getSort().";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        return self::fillObjects($doFetch, $objInterface);
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
    private static function fillObjects($doFetch, $objInterface) {
        $objectIndex = 0;
        $interfaceObjects = array();
        if($doFetch != null)
        {
            while($row = $doFetch->fetch_assoc())
            {
                $objInterface->setPKValue($row[$objInterface->getPKFieldName()]);
                foreach ($objInterface->fields as $value) {
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

    /**
     * Return value in SQL format
     * 
     * @param IField $iField
     * 
     * @param any $value
     * 
     * @return string
     */
    private static function convertToSQLFormat($iField, $value) {
        if(!is_null($iField->getDefault()) && $value == $iField->getDefault())
            return $iField->getDefault();
        if(SQL_FORMAT[$iField->getFormat()] && $value != "null")
            return "'".$value."'";
        return $value;
    }
}
?>
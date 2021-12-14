<?php
namespace backint\core;

use backint\core\DBObj;
use backint\core\ErrObj;
use backint\core\Model;
use Exception;
use SQL;

class QuickQuery implements iQuickQuery {

    private DBObj $dbObject;

    public function __construct(DBObj $_db) {
        $this->dbObject = $_db;
    }

    /**
     * Make an insert into a table
     * 
     * @param Model $objModel
     * 
     * @return ErrObj
     */
    public function insert($objModel) {
        try {
            $sqlQuery = "INSERT INTO ".$objModel->getTableName()." (";
            $sqlValues = "(";
            $index = 0;
            foreach ($objModel->fields as $key => $field) {
                if($index > 0)
                {
                    $sqlQuery .= ",";
                    $sqlValues .= ",";
                }
                $columnName = $key;
                $fieldValue = $field->value;
                $sqlQuery .= $columnName;
                $fieldValue = Model::nullPropagation($field);
                $sqlValues .= $this->convertToSQLFormat($field, $fieldValue);
                $index++;
            }
            $sqlQuery .= ")";
            $sqlValues .= ");";
            $sqlQuery .= " VALUES ".$sqlValues;
            $err = $this->dbObject->doQuery($sqlQuery);
        } catch (Exception $ex) {
            $err = new ErrObj($ex, Http::CONFILCT);
        }
        return $err;
    }

    /**
     * Make a delete in a table using a Model with an PK Id previously set
     * 
     * @param Model $objModel
     * 
     * @return ErrObj
     */
    public function delete($objModel) {
        try {
            $sqlQuery = "DELETE FROM ".$objModel->getTableName()." WHERE ".$objModel->getPKFieldName()." = ".$objModel->getPKValue();
            $err = $this->dbObject->doQuery($sqlQuery);
        } catch (Exception $ex) {
            $err = new ErrObj($ex, Http::CONFILCT);
        }
        return $err;
    }

    /**
     * Update a record from Database using a Model as new values
     * 
     * @param Model
     * 
     * @return ErrObj
     */
    public function update($objModel) {
        try {
            $sqlQuery = "UPDATE ".$objModel->getTableName()." SET ";
            $index = 0;
            foreach ($objModel->fields as $key => $field) {
                if(!is_null($field->value) && $field->value != '') {
                    if($index > 0)
                    {
                        $sqlQuery .= ",";
                    }
                    $columnName = $key;
                    $fieldValue = $field->value;
                    $sqlQuery .= $columnName." = ";
                    $sqlQuery .= $this->convertToSQLFormat($field, $fieldValue);
                    $index++;
                }
            }
            $sqlQuery .= " WHERE ".$objModel->getPKFieldName()." = ".$objModel->getPKValue().";";
            $err = $this->dbObject->doQuery($sqlQuery);
        } catch (Exception $ex) {
            $err = new ErrObj($ex, Http::CONFILCT);
        }
        return $err;
    }

    /**
     * Make a select and bring just one record
     * 
     * @param Model $objModel
     * 
     * @param QueryBuilder $queryBuilder
     * 
     * @return Model
     * 
     * @return null
     */
    public function selectSimple($objModel, $queryBuilder) {
        $sqlQuery = "SELECT * FROM ".$objModel->getTableName().""
            .$queryBuilder->where()->getFilter()
            .$queryBuilder->orderBy()->getSort()." LIMIT 1;";
        $doFetch = $this->dbObject->getFetchAssoc($sqlQuery);
        return self::fillObject($doFetch, $objModel);
    }

    /**
     * Fill a Model using a MySQLi result object
     * 
     * @param mysqli_result $doFetch
     *  
     * @param Model $objModel
     * 
     * @return Model
     * 
     * @return null
     */
    private static function fillObject($doFetch, $objModel) {
        if(!is_null($doFetch))
        {
            while($row = $doFetch->fetch_assoc())
            {
                $objModel->setPKValue($row[$objModel->getPKFieldName()]);
                foreach ($objModel->fields as $field) {
                    $columnName = $field->getColumnName();
                    if($row[$columnName] != "")
                        $objModel->fields[$columnName]->value = $row[$columnName];
                    else
                        $objModel->fields[$columnName]->value = "null";
                }
            }
            return $objModel;
        }
        return null;
        
    }

    /**
     * Make a select and bring all records using a query builder to set a filter
     * 
     * @param Model $objModel
     * 
     * @param QueryBuilder $queryBuilder
     * 
     * @return array Model
     * 
     * @return null
     */
    public function selectMultiple($objModel, $queryBuilder) {
        $sqlQuery = "SELECT * FROM ".$objModel->getTableName().""
            .$queryBuilder->where()->getFilter()
            .$queryBuilder->orderBy()->getSort().";";
        $doFetch = $this->dbObject->getFetchAssoc($sqlQuery);
        return $this->fillObjects($doFetch, $objModel);
    }

    /**
     * Fill a Model array using a MySQLi result object
     * 
     * @param mysqli_result $doFetch
     *  
     * @param Model $objModel
     * 
     * @return array Model
     * 
     * @return null
     */
    private function fillObjects($doFetch, $objModel) {
        $objectIndex = 0;
        $modelObjects = null;
        if(!is_null($doFetch))
        {
            $modelObjects = array();
            while($row = $doFetch->fetch_assoc())
            {
                $objModel->setPKValue($row[$objModel->getPKFieldName()]);
                foreach ($objModel->fields as $value) {
                    $columnName = $value->getColumnName();
                    
                    if($row[$columnName] != "")
                        $objModel->fields[$columnName]->value = $row[$columnName];
                    else
                        $objModel->fields[$columnName]->value = "null";
                    $new[$columnName] = clone $objModel->fields[$columnName];
                }
                $modelObjects[$objectIndex] = clone $objModel;
                $modelObjects[$objectIndex]->fields = $new;
                $objectIndex++;
            }
        }
        return $modelObjects;
    }

    /**
     * Return value in SQL format
     * 
     * @param ModelField $iField
     * 
     * @param any $value
     * 
     * @return string
     */
    private function convertToSQLFormat($field, $value) {
        if(!is_null($field->getDefault()) && $value == $field->getDefault())
            return $field->getDefault();
        if(SQL::SQL_FORMAT[$field->getFormat()] && $value != "null")
            return "'".$value."'";
        return $value;
    }
}
?>
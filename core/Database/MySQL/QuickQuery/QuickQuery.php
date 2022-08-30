<?php
namespace backint\core\db;

use backint\core\db\builder\QueryBuilder;
use backint\core\Result;
use backint\core\Model;
use Exception;

class RelationalQuickQuery implements iQuickQuery {

    private $dbObject;

    private $builder;

    public function __construct(iDBObj $_db, QueryBuilder $_builder) {
        $this->dbObject = $_db;
        $this->builder = $_builder;
    }

    /**
     * Make an insert into a table
     * 
     * @param Model $objModel
     * 
     * @return Result
     */
    public function insert($objModel) {
        try {
            $this->builder->insert()->setInsertModel($objModel);
            foreach ($objModel->fields as $field) {
                $this->builder->insert()->addField($field);
            }
            $err = $this->dbObject->doQueryPost($this->builder->insert());
        } catch (Exception $ex) {
            $err = new Result($ex, false);
        }
        return $err;
    }

    /**
     * Make a delete in a table using a Model with an PK Id previously set
     * 
     * @param Model $objModel
     * 
     * @return Result
     */
    public function delete($objModel) {
        try {
            $this->builder->delete()->setDeleteModel($objModel);
            $this->builder->delete()->where()->addPKFilter($objModel, $objModel->getIdField()->value);
            $err = $this->dbObject->doQueryDelete($this->builder->delete());
        } catch (Exception $ex) {
            $err = new Result($ex, false);
        }
        return $err;
    }

    /**
     * Update a record from Database using a Model as new values
     * 
     * @param Model
     * 
     * @return Result
     */
    public function update($objModel) {
        try {
            $this->builder->update()->setUpdateModel($objModel);
            foreach ($objModel->fields as $field) {
                if(!is_null($field->value)) {
                    $this->builder->update()->addField($field);
                }
            }
            $this->builder->update()->where()->addPKFilter($objModel, $objModel->getIdField()->value);
            $err = $this->dbObject->doQueryPut($this->builder->update());
        } catch (Exception $ex) {
            $err = new Result($ex, true);
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
        $queryBuilder->select()->limit()->setIndex(0);
        $queryBuilder->select()->limit()->setRecords(1);
        $queryBuilder->select()->setSelectModel($objModel);
        $doFetch = $this->dbObject->getFetchAssoc($queryBuilder->select());
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
                $objModel->getIdField()->value = $row[$objModel->getIdField()->getFieldName()];
                foreach ($objModel->fields as $field) {
                    $columnName = $field->getFieldName();
                    if($row[$columnName] != "")
                        $objModel->fields[$columnName]->value = $row[$columnName];
                    else
                        $objModel->fields[$columnName]->value = null;
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
        $queryBuilder->select()->setSelectModel($objModel);
        $doFetch = $this->dbObject->getFetchAssoc($queryBuilder->select());
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
                foreach ($objModel->fields as $value) {
                    $columnName = $value->getFieldName();
                    
                    if($row[$columnName] != "")
                        $objModel->fields[$columnName]->value = $row[$columnName];
                    else
                        $objModel->fields[$columnName]->value = null;
                    $new[$columnName] = clone $objModel->fields[$columnName];
                }
                $modelObjects[$objectIndex] = clone $objModel;
                $modelObjects[$objectIndex]->fields = $new;
                if(!is_null($objModel->getIdField())) {
                    $objModel->getIdField()->value = $row[$objModel->getIdField()->getFieldName()];
                    $clonId = clone $objModel->getIdField();
                    $modelObjects[$objectIndex]->setIdField($clonId);
                }
                $objectIndex++;
            }
        }
        return $modelObjects;
    }
}
?>
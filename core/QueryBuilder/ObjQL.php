<?php
namespace backint\core;

use backint\core\DBObj;
use backint\core\QueryBuilder;
use SQL;

class ObjQL {

    /**
     * JSON structure in an array
     * 
     * @var array
     */
    private $fields;

    /**
     * MySQL query
     * 
     * @var string
     */
    private $query;

    /**
     * Main data source
     * 
     * @var string
     */
    private $source;

    /**
     * Number of records
     * 
     * @var int
     */
    public $num_records = 0;

    /**
     * Assoc array
     * 
     * @var array
     */
    public $result = array();

    /**
     * Constructor
     * 
     * @param array $fields Json array
     */
    public function __construct($fields) {
        $this->fields = $fields;
        $this->query = "";
    }

    /**
     * Add a field
     * 
     * @param Model $model
     * 
     * @param ModelField $field
     * 
     * @param string $alias
     * 
     * @return void
     */
    public function addField($model, $field, $alias = "") {
        if(strlen($this->query) > 0)
            $this->query .= ", ".$model->getTableName().".".$field->getColumnName();
        else
            $this->query .= "SELECT ".$model->getTableName().".".$field->getColumnName();
        if(strlen($alias) > 0)
            $this->query .= " AS '".$alias."'";
    }

    /**
     * Set main data source
     * 
     * @param Model $model
     * 
     * @return void
     */
    public function setDataSource($model) {
        $this->source = $model->getTableName();
    }

    /**
     * Create a MySQL query
     * 
     * @param Model $model
     * 
     * @param string $alias
     * 
     * @return void
     */
    public function addPKField($model, $alias = "") {
        if(strlen($this->query) > 0)
            $this->query .= ", ".$model->getTableName().".".$model->getPKFieldName();
        else
            $this->query .= "SELECT ".$model->getTableName().".".$model->getPKFieldName();
        if(strlen($alias) > 0)
            $this->query .= " AS '".$alias."'";
    }

    /**
     * Get a JSON string from the internal query
     * 
     * @param QueryBuilder
     * 
     * @return string
     */
    public function buildJsonFromQuery($queryBuilder) {
        $this->query .= " FROM ".$this->source." "
            .$queryBuilder->join()->getJoin()
            .$queryBuilder->where()->getFilter()
            .$queryBuilder->orderBy()->getSort()
            .$queryBuilder->limit()->getLimit();
        $dbObj = new DBObj();
        $doFetch = $dbObj->getFetchAssoc($this->query);
        $this->num_records = $dbObj->getNumRecords();
        $json = '';
        $jsonFields = '';
        if($this->num_records == 0) {
            $json .= '{}';
        } else {
            $firstJson = 0;
            while($row = $doFetch->fetch_assoc())
            {
                array_push($this->result, $row);
                if($firstJson > 0)
                    $jsonFields .= ',';
                $firstField = 0;
                $jsonFields .= '{';
                foreach ($this->fields as $field) {
                    if($firstField > 0)
                        $jsonFields .= ',';
                    if($field[1] != SQL::JSON)
                    {
                        if(SQL::SQL_FORMAT[$field[1]])
                        {
                            $jsonFields .= '"'.$field[0].'":'.'"'.$row[$field[0]].'"';
                        }
                        else
                        { 
                            if($row[$field[0]] == null)
                                $jsonFields .= '"'.$field[0].'":'.'null';
                            else
                                $jsonFields .= '"'.$field[0].'":'.''.$row[$field[0]].'';
                        }
                    }
                    else
                    {
                        if($field[1] == SQL::JSON)
                        {
                            //SQLhelper $fieldName[2]
                            $field[2]->getControllerFilter()->setDynamicValue($row[$field[2]->getControllerFilter()->getDynamicFieldName()]);
                            $field[2]->getControllerFilter()->buildDynamicFilter();
                            //ObjQL $fieldName[3]
                            $jsonFields .=  '"'.$field[0].'":'.$field[3]->buildJsonFromQuery($field[2]);
                        }
                    }
                    $firstField++;
                }
                $jsonFields .= '}';
                $firstJson++;
            }
            if($this->num_records > 1)
                $json .= '['.$jsonFields.']';
            else {
                if($jsonFields == "")
                    $json .= '{}';
                else
                    $json .= $jsonFields;
            }
        }
        return $json;
    }

    /**
     * Get query
     * 
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }
}
?>
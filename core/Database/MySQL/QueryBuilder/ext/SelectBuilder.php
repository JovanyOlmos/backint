<?php
namespace backint\core\db\builder;

use backint\core\Model;
use backint\core\ModelField;
use Exception;

class MySQLSelectBuilder extends SelectBuilder {

    /**
     * MySQL Sentence
     * 
     * @var string
     */
    private $query = "";

    public function __construct() {
        parent::__construct();
        $this->setWhere(new MySQLWhereBuilder());
        $this->setOrderBy(new MySQLOrderBuilder());
        $this->setLimit(new MySQLLimitBuilder());
        $this->setJoin(new MySQLJoinBuilder());
        $this->setGroupBy(new MySQLGroupBuilder());
    }

    /**
     * Add a model field into the select list
     * 
     * @param Model
     * 
     * @param ModelField
     * 
     * @return void
     */
    public function addField($model, $modelField) {
        $this->setField("`".$model->getModelName()."`".".`".$modelField->getFieldName()."`");
    }

    /**
     * Return query. Return all if there not fields added
     * 
     * @return string
     */
    public function getSelect(): string {
        if($this->getFrom() == "") {
            throw new Exception("Model is not set", 1);
            die();
        }
        if(sizeof($this->getFields()) > 0) {
            $this->query = "SELECT ";
            $index = 0;   
            foreach ($this->getFields() as $field) {
                if($index > 0) {
                    $this->query .= ", ";
                }
                $this->query .= $field;
                $index++;
            }
            return $this->query." FROM `".$this->getFrom()."`"
                .$this->join()->getJoin()
                .$this->where()->getFilter()
                .$this->orderBy()->getSort()
                .$this->groupBy()->getGroupBy()
                .$this->limit()->getLimit();
        }
        return  "SELECT * FROM `".$this->getFrom()."`"
            .$this->join()->getJoin()
            .$this->where()->getFilter()
            .$this->orderBy()->getSort()
            .$this->groupBy()->getGroupBy()
            .$this->limit()->getLimit();
    }

}
?>
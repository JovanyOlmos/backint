<?php
namespace backint\core\db\builder;

use backint\core\Model;
use backint\core\ModelField;

abstract class SelectBuilder {

    private $where;

    private $order;

    private $limit;

    private $join;

    private $group;

    /**
     * Array with all fields to select
     * 
     * @var array 
     */
    private $fields;

    /**
     * Select from model
     * 
     * @var string;
     */
    private $from;

    /**
     * Constructor
     */
    public function __construct() {
        $this->fields = array();
        $this->from = "";
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
    public abstract function addField($model, $modelField);

    /**
     * Set main model to select
     * 
     * @var Model
     * 
     * @return void
     */
    public function setSelectModel($model) {
        $this->from = $model->getModelName();
    }

    /**
     * Get main model to select
     * 
     * @return string
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * Get fields to select
     * 
     * @return mixed
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * Push a field into select fields array
     * 
     * @param string
     * 
     * @return void
     */
    public function setField($stringField) {
        array_push($this->fields, $stringField);
    }

    /**
     * Return query. Return all if there not fields added
     * 
     * @return string
     */
    public abstract function getSelect();

    /**
     * Get where builder
     * 
     * @return WhereBuilder
     */
    public function where() {
        return $this->where;
    }

    /**
     * Get order builder
     * 
     * @return OrderBuilder
     */
    public function orderBy() {
        return $this->order;
    }

    /**
     * Get join builder
     * 
     * @return JoinBuilder
     */
    public function join() {
        return $this->join;
    }

    /**
     * Get limit builder
     * 
     * @return LimitBuilder
     */
    public function limit() {
        return $this->limit;
    }

    /**
     * Get group builder
     * 
     * @return GroupBuilder
     */
    public function groupBy() {
        return $this->group;
    }

    /**
     * Set where builder
     * 
     * @param WhereBuilder
     * 
     * @return void
     */
    public function setWhere($where) {
        $this->where = $where;
    }

    /**
     * Set order builder
     * 
     * @param OrderBuilder
     * 
     * @return void
     */
    public function setOrderBy($order) {
        $this->order = $order;
    }

    /**
     * Set join builder
     * 
     * @param JoinBuilder
     * 
     * @return void
     */
    public function setJoin($join) {
        $this->join = $join;
    }

    /**
     * Set limit builder
     * 
     * @param LimitBuilder
     * 
     * @return void
     */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

    /**
     * Set group builder
     * 
     * @param GroupBuilder
     * 
     * @return void
     */
    public function setGroupBy($group) {
        $this->group = $group;
    }

}
?>
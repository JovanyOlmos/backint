<?php
namespace backint\core\db\builder;

use backint\core\Model;

abstract class DeleteBuilder {

    /**
     * Delete model
     * 
     * @var WhereBuilder
     */
    private $where;

    /**
     * Delete model
     * 
     * @var Model
     */
    private $model = null;

    /**
     * Construct
     */
    public function __construct() {
        
    }

    /**
     * Get main model to delete
     * 
     * @return Model
     */
    public function getDeleteModel() {
        return $this->model;
    }

    /**
     * Set main model to delete
     * 
     * @param Model
     * 
     * @return void
     */
    public function setDeleteModel($model) {
        $this->model = $model;
    }

    /**
     * Return query
     * 
     * @return string
     */
    public abstract function getDelete(): string;

    /**
     * Get where builder
     * 
     * @return WhereBuilder
     */
    public function where() {
        return $this->where;
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
}
?>